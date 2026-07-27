"""Fork-invariant gate: the Kimi SEO rebrand must survive upstream merges.

Runs scripts/check_rebrand.py against the repo and asserts every invariant
holds: no ``.config/claude-seo``, ``bin/claude-seo``, ``claude-seo
run|setup|doctor``, ``Claude SEO`` brand, or unknown ``CLAUDE_SEO_*`` env vars
outside the attribution allowlist; and the required Kimi SEO artifacts
(config dir reference in scripts/, executable bin/kimi-seo, kimi.plugin.json
with ``"name": "kimi-seo"``) are present.
"""
import json
import os
import subprocess
import sys

REPO = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SCRIPT = os.path.join(REPO, "scripts", "check_rebrand.py")


def run_checker():
    proc = subprocess.run([sys.executable, SCRIPT, "--json"],
                          capture_output=True, text=True, cwd=REPO)
    return proc, json.loads(proc.stdout)


def failing_checks(result):
    return [c for c in result["checks"] if c["status"] == "FAIL"]


def test_rebrand_invariants_hold():
    proc, result = run_checker()
    failed = failing_checks(result)
    assert failed == [], "rebrand invariant failures: " + json.dumps(failed, indent=2)
    assert result["status"] == "PASS"
    assert proc.returncode == 0


def test_forbidden_patterns_absent():
    _, result = run_checker()
    forbidden = [c for c in result["checks"] if c["name"].startswith("forbidden:")]
    assert forbidden, "expected forbidden-pattern checks in output"
    offenders = {c["name"]: c["findings"] for c in forbidden if c["findings"]}
    assert offenders == {}, "forbidden patterns found: " + json.dumps(offenders, indent=2)


def test_required_artifacts_present():
    _, result = run_checker()
    required = {c["name"]: c for c in result["checks"]
                if c["name"].startswith("required:")}
    assert set(required) == {"required:config-dir", "required:cli-launcher",
                             "required:plugin-manifest"}
    missing = {n: c["findings"] for n, c in required.items() if c["status"] != "PASS"}
    assert missing == {}, "required artifacts missing: " + json.dumps(missing, indent=2)


def test_checker_scans_whole_tree():
    _, result = run_checker()
    assert result["files_scanned"] > 300


CANARY = os.path.join(REPO, ".secret-canary-tmp")


def run_checker_with_canary(content):
    with open(CANARY, "w", encoding="utf-8") as fh:
        fh.write(content)
    try:
        return run_checker()
    finally:
        os.unlink(CANARY)


def leaked_secrets_check(result):
    return next(c for c in result["checks"]
                if c["name"] == "forbidden:leaked-secrets")


def test_secret_canary_detected():
    fake_keys = (
        'KIMI_KEY = "sk-a1b2c3d4e5f6g7h8i9j0"\n'      # sk-* shape
        'GOOGLE = "AIzaSyA1b2c3d4e5f6g7h8i9j0k1l2m3n4o5pqr"\n'  # AIza* shape
        'GITHUB = "ghp_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r"\n'
        "-----BEGIN PRIVATE KEY-----\n"
        'api_key = "X9vQw2Er5Ty8Ui1Op4As6Df7Gh"\n'     # high-entropy literal
    )
    _, result = run_checker_with_canary(fake_keys)
    check = leaked_secrets_check(result)
    assert check["status"] == "FAIL"
    assert result["status"] == "FAIL"
    assert len(check["findings"]) >= 5  # one per planted secret line


def test_secret_placeholders_not_flagged():
    placeholders = (
        'KIMI_KEY = "sk-your-key-here"\n'
        'api_key = "<your-api-key>"\n'
        "api_key = \"${KIMI_API_KEY}\"\n"
        'access_token = "xxxxxxxxxxxxxxxxxxxxxxxx"\n'
        'password = "example-password-value"\n'
    )
    proc, result = run_checker_with_canary(placeholders)
    check = leaked_secrets_check(result)
    assert check["status"] == "PASS", check["findings"]
    assert proc.returncode == 0
