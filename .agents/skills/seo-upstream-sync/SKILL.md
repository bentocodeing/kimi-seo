---
name: seo-upstream-sync
description: Sync the kimi-seo fork with upstream claude-seo releases — merge upstream/main into the kimi branch, resolve rebrand conflicts, verify invariants, run tests
whenToUse: Use when the user asks to "update from upstream", "sync fork", "merge upstream", mentions a "new claude-seo release", or wants the kimi branch brought up to date with AgriciDaniel/claude-seo.
---

# Upstream Sync Runbook (kimi-seo fork)

Repo model: remote `upstream` = AgriciDaniel/claude-seo, remote `origin` =
bentocodeing/kimi-seo (the fork). `main` tracks upstream untouched; `kimi` =
upstream + rebrand; releases are tagged from `kimi`.

## Rebrand invariants (surface, not plumbing)

- NEVER rename: skill dirs/files under `skills/` (all `seo-*`), agent files in
  `agents/`, script names in `scripts/`, internal paths.
- MUST stay renamed: config dir `~/.config/kimi-seo`, CLI `bin/kimi-seo` (and
  `kimi-seo run|setup|doctor` invocations), display brand "Kimi SEO", fork
  repo URLs `github.com/bentocodeing/kimi-seo`, env vars `KIMI_SEO_*`.
  (Inherited plumbing overrides `CLAUDE_SEO_PYTHON`, `CLAUDE_SEO_DATA_DIR`,
  `CLAUDE_SEO_KEEP_TEMP` are kept deliberately as back-compat fallbacks.)
- MUST stay as-is (attribution): `github.com/AgriciDaniel/claude-seo` URLs,
  "forked from" notes, marketplace slug `claude-seo@agricidaniel-claude-seo`,
  CHANGELOG.md history, CONTRIBUTORS.md, CITATION.cff, third-party fork
  mentions (ivankuznetsov, AI-Marketing-Hub, matej-marjanovic),
  `skills/*/LICENSE.txt` upstream URLs.

## 1. Pre-flight

- Be on `kimi` with a clean working tree. If dirty, stop and tell the user.
- `git fetch upstream --tags`
- Report: `git rev-list --count kimi..upstream/main` commits behind, latest
  upstream tag `git describe --tags --abbrev=0 upstream/main`.
- List files the release touches for step 4:
  `git diff --name-only $(git merge-base kimi upstream/main)..upstream/main`

## 2. Update main

```
git checkout main && git merge --ff-only upstream/main && git push origin main && git checkout kimi
```

If `--ff-only` fails, `main` was polluted — stop and report; do not force.

## 3. Merge into kimi

`git merge upstream/main --no-ff`. On conflicts, keep upstream's functional
changes and the fork's naming per the invariants above. Hot spots:
`README.md`, `install.sh`, `install.ps1`, `.claude-plugin/plugin.json`,
`pyproject.toml`, `skills/*/SKILL.md` (description lines), `docs/`.

If the merge is too messy to resolve confidently: `git merge --abort` and
report the conflicting files — do not guess.

## 4. Re-apply invariants after ANY merge (clean or not)

Upstream files added/changed in the release may carry old branding. Scan the
merge range for `Claude SEO`, `.config/claude-seo`, `bin/claude-seo`,
`claude-seo run|setup|doctor`, and new `CLAUDE_SEO_*` env vars; rebrand each
hit except allowlisted attribution content (CHANGELOG.md, CONTRIBUTORS.md,
CITATION.cff, LICENSE.txt files, lines naming upstream URLs/forks).

## 5. Verify — all four must hold before any release step

- `./bin/kimi-seo run check_rebrand.py` → PASS (exit 0). This includes the
  `forbidden:leaked-secrets` scan: generic credential shapes (sk-*, Google
  AIza*, GitHub gh*_*/github_pat_*, AWS AKIA*, Slack xox*, private-key
  blocks, high-entropy literals assigned to api_key/secret/token/password
  variables) must not appear anywhere in the tree, branding allowlist
  included. If it FAILs, stop: do not "fix" by allowlisting — find who
  introduced the file (upstream? a conflict resolution? the user?) and get
  it removed or moved to `~/.config/kimi-seo/` (outside the repo).
- `.venv/bin/python -m pytest tests/ -q` → green except the 2 known
  `tests/test_sync_flow.py` GitHub rate-limit failures
- `./bin/kimi-seo run portability_check.py` → pass
- Secrets hygiene: credentials NEVER live in the repo — not in commits, not
  in docs, not in examples. Never paste, print, or commit a real key while
  resolving this sync; never tune the detection patterns to match (or
  exclude) anyone's actual keys. Placeholders only (`sk-your-key-here`).

## 6. Release

1. Bump the fork version on the fork's own semver line (upstream v2.2.5 →
   fork v1.1.0-style bump, not the upstream number) in `kimi.plugin.json`,
   `pyproject.toml`, and skill/agent metadata that carries it.
2. Update the `KIMI_SEO_TAG` default in `install.sh` (and `install.ps1`).
3. Prepend a mapped entry in the "Kimi SEO fork releases" section of
   CHANGELOG.md: "Kimi SEO vX.Y.Z — based on upstream claude-seo vA.B.C".
4. Show the user the diff and let them commit, or get explicit approval for:
   `git tag -a vX.Y.Z -m "Kimi SEO vX.Y.Z — based on upstream claude-seo vA.B.C"`
   then `git push origin kimi --tags`.

## Guardrails

- NEVER commit or push without explicit user approval (step 2's
  `push origin main` excepted as part of the approved sync).
- NEVER force-push. NEVER rename skill/script/agent file names.
- If anything diverges from this runbook (non-ff main, dirty tree, unresolved
  conflicts), stop and report instead of improvising.
