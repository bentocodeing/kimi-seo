#!/usr/bin/env python3
"""Fork-invariant verifier for the Kimi SEO rebrand of AgriciDaniel/claude-seo.

The fork rebrands the surface, not the plumbing: skill/agent/script file names
stay as upstream, but the config dir, CLI, display brand, repo URLs and
user-facing env vars must be the Kimi SEO ones. This script mechanically
checks those invariants so an upstream merge cannot silently reintroduce old
branding.

Forbidden patterns (fail outside the allowlist):
  - ``.config/claude-seo``           config dir must be ~/.config/kimi-seo
  - ``bin/claude-seo``               old CLI path (must be bin/kimi-seo)
  - ``claude-seo run|setup|doctor``  old CLI invocations
  - ``Claude SEO``                   old display brand
  - ``CLAUDE_SEO_*`` env vars        user-facing vars are KIMI_SEO_*
      (the inherited back-compat plumbing overrides CLAUDE_SEO_PYTHON,
      CLAUDE_SEO_DATA_DIR and CLAUDE_SEO_KEEP_TEMP are kept on purpose —
      see the rebrand commit — and are not flagged)

Allowlisted (attribution/history, never flagged):
  CHANGELOG.md, CONTRIBUTORS.md, CITATION.cff, CODE_OF_CONDUCT.md,
  SECURITY.md, .github/ISSUE_TEMPLATE/, skills/**/LICENSE.txt,
  extensions/**/LICENSE.txt, this script and its test, and the
  seo-upstream-sync maintainer skill. Line-level: any line containing
  AgriciDaniel/claude-seo, claude-seo@agricidaniel-claude-seo,
  ivankuznetsov, AI-Marketing-Hub, matej-marjanovic, claude-seo.md, or
  "forked from" / "Fork of" attribution phrasing is OK in any file.

Required patterns (fail if missing):
  - ``~/.config/kimi-seo`` appears in at least one scripts/*.py file
  - ``bin/kimi-seo`` exists and is executable
  - ``kimi.plugin.json`` exists at repo root with ``"name": "kimi-seo"``

Leaked-secret scan (fail on any hit):
  Generic credential shapes — sk-*, Google AIza*, GitHub gh*_*/github_pat_*,
  AWS AKIA*, Slack xox*, private-key blocks, and long literal values assigned
  to api_key/secret/token/password-style variables. This scan applies to
  EVERY text file (the attribution allowlist only covers branding, never
  secrets) except this script and its test, which carry patterns/fixtures as
  literals. Placeholder values (your-key, <...>, ${...}, example, dummy…)
  and low-entropy values are not flagged.

Usage:
    python3 scripts/check_rebrand.py [--json]
    ./bin/kimi-seo run check_rebrand.py

Exit codes: 0 = all invariants hold, 1 = at least one check failed.
"""
import argparse
import json
import os
import re
import subprocess
import sys

REPO = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

EXCLUDE_DIRS = {".git", ".venv", "__pycache__", ".pytest_cache",
                "ms-playwright"}  # vendored browser binaries from `kimi-seo setup`

ALLOWLIST_FILES = {
    "CHANGELOG.md", "CONTRIBUTORS.md", "CITATION.cff", "CODE_OF_CONDUCT.md",
    "SECURITY.md",
    # Self-exclusion: the checker and its test carry the patterns as literals.
    "scripts/check_rebrand.py", "tests/test_check_rebrand.py",
    # Maintainer runbook documents the forbidden patterns on purpose.
    ".agents/skills/seo-upstream-sync/SKILL.md",
}
ALLOWLIST_PREFIXES = (
    ".github/ISSUE_TEMPLATE/",
)
ALLOWLIST_LINE_PATTERNS = (
    "AgriciDaniel/claude-seo",
    "claude-seo@agricidaniel-claude-seo",
    "ivankuznetsov",
    "AI-Marketing-Hub",
    "matej-marjanovic",
    "claude-seo.md",  # upstream website
)
ALLOWLIST_LINE_RE = re.compile(r"forked from|fork of", re.IGNORECASE)

# Inherited upstream plumbing overrides, kept deliberately for back-compat
# (the rebrand renamed only user-facing env vars such as CLAUDE_SEO_TAG ->
# KIMI_SEO_TAG). Any *other* CLAUDE_SEO_* var is flagged.
KNOWN_PLUMBING_VARS = {
    "CLAUDE_SEO_PYTHON", "CLAUDE_SEO_DATA_DIR", "CLAUDE_SEO_KEEP_TEMP",
}

FORBIDDEN = [
    ("config-dir", re.compile(r"\.config/claude-seo"),
     "config dir must be ~/.config/kimi-seo"),
    ("cli-path", re.compile(r"bin/claude-seo"),
     "old CLI path (must be bin/kimi-seo)"),
    ("cli-invocation", re.compile(r"\bclaude-seo (?:run|setup|doctor)\b"),
     "old CLI invocation (must be kimi-seo ...)"),
    ("brand", re.compile(r"Claude SEO"),
     "old display brand (must be Kimi SEO)"),
]
ENV_VAR_RE = re.compile(r"\b(CLAUDE_SEO_[A-Z0-9_]+)")

# --- Leaked-secret detection -------------------------------------------------
# Generic credential shapes only. These patterns must NEVER be tuned to a
# maintainer's real keys: the point is to catch any committed credential that
# looks like a credential.
SECRET_PATTERNS = [
    ("sk-* API key", re.compile(r"\bsk-[A-Za-z0-9]{16,}")),
    ("Google API key", re.compile(r"\bAIza[0-9A-Za-z_-]{35}")),
    ("GitHub token", re.compile(
        r"\b(?:gh[pousr]_[A-Za-z0-9]{20,}|github_pat_[A-Za-z0-9_]{22,})")),
    ("AWS access key", re.compile(r"\bAKIA[0-9A-Z]{16}\b")),
    ("Slack token", re.compile(r"\bxox[baprs]-[A-Za-z0-9-]{10,}")),
    ("private key block", re.compile(
        r"-----BEGIN (?:RSA |EC |OPENSSH |PGP )?PRIVATE KEY-----")),
]
# Long literal assigned to a credential-named variable: api_key = "…",
# access_token: "…", AUTH_PASSWORD='…', etc.
SECRET_ASSIGN_RE = re.compile(
    r"(?i)\b(?:api[_-]?key|api[_-]?secret|secret[_-]?key|access[_-]?token|"
    r"auth[_-]?token|client[_-]?secret|password)\b[\s\"']*[:=][\s\"']*"
    r"([A-Za-z0-9/+_=-]{20,})")
PLACEHOLDER_VALUE_RE = re.compile(
    r"(?i)^(?:\$|<|\{|%|your\b|xxx|\*+|placeholder|example|sample|dummy|"
    r"changeme|redacted|replace|insert|todo|none|null|false|true|abcd)")


def looks_like_placeholder(value):
    if PLACEHOLDER_VALUE_RE.match(value):
        return True
    return len(set(value.lower())) <= 3  # low-entropy filler e.g. aaaa…, 123123…


# Files carrying patterns/fixtures as literals — excluded from the secret scan
# only (not from the branding allowlist logic above, which has its own list).
SECRET_SCAN_EXCLUDE = {"scripts/check_rebrand.py", "tests/test_check_rebrand.py"}


def repo_files():
    """Tracked + untracked-but-not-ignored files; os.walk fallback off-git."""
    try:
        out = subprocess.run(
            ["git", "-C", REPO, "ls-files", "-z",
             "--cached", "--others", "--exclude-standard"],
            capture_output=True, check=True).stdout
        paths = [p for p in out.decode("utf-8", "replace").split("\0") if p]
    except (OSError, subprocess.CalledProcessError):
        paths = []
        for root, dirs, files in os.walk(REPO):
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            for name in files:
                paths.append(os.path.relpath(os.path.join(root, name), REPO))
    return sorted(p for p in paths if p.split("/")[0] not in EXCLUDE_DIRS)


def read_text(rel):
    path = os.path.join(REPO, rel)
    try:
        if os.path.getsize(path) > 2_000_000:
            return None
        with open(path, "rb") as fh:
            return fh.read().decode("utf-8")
    except (OSError, UnicodeDecodeError):
        return None  # binary or unreadable


def file_allowlisted(rel):
    if rel in ALLOWLIST_FILES:
        return True
    if rel.startswith(ALLOWLIST_PREFIXES):
        return True
    if re.match(r"(skills|extensions)/.*/LICENSE\.txt$", rel):
        return True
    return False


def line_allowlisted(line):
    if any(p in line for p in ALLOWLIST_LINE_PATTERNS):
        return True
    return bool(ALLOWLIST_LINE_RE.search(line))


def check_forbidden(files):
    """One result per forbidden pattern, findings as file:line entries."""
    results = []
    per_file = []  # (rel, lineno, line) for non-allowlisted text files
    for rel in files:
        if file_allowlisted(rel):
            continue
        text = read_text(rel)
        if text is None:
            continue
        for lineno, line in enumerate(text.splitlines(), 1):
            if not line_allowlisted(line):
                per_file.append((rel, lineno, line))

    for name, pattern, why in FORBIDDEN:
        findings = [f"{rel}:{lineno}: {line.strip()[:120]}"
                    for rel, lineno, line in per_file if pattern.search(line)]
        results.append({"name": f"forbidden:{name}", "why": why,
                        "status": "FAIL" if findings else "PASS",
                        "findings": findings})

    env_findings = []
    for rel, lineno, line in per_file:
        for var in ENV_VAR_RE.findall(line):
            if var not in KNOWN_PLUMBING_VARS:
                env_findings.append(f"{rel}:{lineno}: {var} "
                                    f"(user-facing env vars must be KIMI_SEO_*)")
    results.append({"name": "forbidden:env-vars",
                    "why": "user-facing env vars must be KIMI_SEO_* "
                           "(known plumbing overrides excepted)",
                    "status": "FAIL" if env_findings else "PASS",
                    "findings": env_findings})
    return results


def check_secrets(files):
    """Leaked-credential scan over every text file (attribution allowlist
    does NOT apply — secrets are never OK, whatever the file)."""
    findings = []
    for rel in files:
        if rel in SECRET_SCAN_EXCLUDE:
            continue
        text = read_text(rel)
        if text is None:
            continue
        for lineno, line in enumerate(text.splitlines(), 1):
            for label, pattern in SECRET_PATTERNS:
                if pattern.search(line):
                    findings.append(f"{rel}:{lineno}: {label}")
                    break
            else:
                match = SECRET_ASSIGN_RE.search(line)
                if match and not looks_like_placeholder(match.group(1)):
                    findings.append(f"{rel}:{lineno}: credential-named "
                                    f"variable with high-entropy literal")
    return [{"name": "forbidden:leaked-secrets",
             "why": "no API keys, tokens or private keys may be committed",
             "status": "FAIL" if findings else "PASS",
             "findings": findings}]


def check_required(files):
    results = []

    hits = [rel for rel in files
            if rel.startswith("scripts/") and rel.endswith(".py")
            and rel != "scripts/check_rebrand.py"
            and ".config/kimi-seo" in (read_text(rel) or "")]
    results.append({"name": "required:config-dir",
                    "why": "~/.config/kimi-seo must appear in scripts/",
                    "status": "PASS" if hits else "FAIL",
                    "findings": [] if hits else
                    ["no scripts/*.py references ~/.config/kimi-seo"]})

    launcher = os.path.join(REPO, "bin", "kimi-seo")
    ok = os.path.isfile(launcher) and os.access(launcher, os.X_OK)
    results.append({"name": "required:cli-launcher",
                    "why": "bin/kimi-seo must exist and be executable",
                    "status": "PASS" if ok else "FAIL",
                    "findings": [] if ok else
                    ["bin/kimi-seo missing or not executable"]})

    plugin_path = os.path.join(REPO, "kimi.plugin.json")
    findings = []
    try:
        with open(plugin_path, encoding="utf-8") as fh:
            name = json.load(fh).get("name")
        if name != "kimi-seo":
            findings.append(f'kimi.plugin.json: "name" is {name!r}, '
                            f'expected "kimi-seo"')
    except FileNotFoundError:
        findings.append("kimi.plugin.json missing at repo root")
    except (OSError, json.JSONDecodeError) as exc:
        findings.append(f"kimi.plugin.json unreadable: {exc}")
    results.append({"name": "required:plugin-manifest",
                    "why": 'kimi.plugin.json must exist with "name": "kimi-seo"',
                    "status": "FAIL" if findings else "PASS",
                    "findings": findings})
    return results


def main():
    ap = argparse.ArgumentParser(description=__doc__.splitlines()[0])
    ap.add_argument("--json", action="store_true", help="JSON output")
    args = ap.parse_args()

    files = repo_files()
    checks = (check_forbidden(files) + check_required(files)
              + check_secrets(files))
    failed = [c for c in checks if c["status"] == "FAIL"]
    result = {"status": "FAIL" if failed else "PASS",
              "checks": checks, "files_scanned": len(files)}

    if args.json:
        print(json.dumps(result, indent=2))
    else:
        for c in checks:
            print(f"{c['status']}: {c['name']} ({c['why']})")
            for f in c["findings"]:
                print(f"  {f}")
        print(f"{result['status']}: {len(failed)}/{len(checks)} checks failed, "
              f"{len(files)} files scanned")
    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
