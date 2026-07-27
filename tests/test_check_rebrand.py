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
