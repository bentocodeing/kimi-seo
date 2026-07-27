# Fork workflow: upstream mirror + product branch

kimi-seo is a fork of [`AgriciDaniel/claude-seo`](https://github.com/AgriciDaniel/claude-seo).
This document is the canonical reference for how work flows between upstream
and this fork. The step-by-step maintainer runbook that operationalizes it
lives at `.agents/skills/seo-upstream-sync/SKILL.md`.

## Topology

```
                upstream                          origin
        AgriciDaniel/claude-seo             bentocodeing/kimi-seo
                   │                                 │
                   │  git fetch upstream --tags      │
                   ▼                                 ▼
              main (mirror)  ───── merge ─────▶  kimi (product)
              pristine copy of                 upstream + Kimi rebrand
              upstream/main                    and adaptations
                                                       │
                                                       ▼
                                            releases tagged from kimi
```

- Remote `upstream` = `AgriciDaniel/claude-seo` (read-only reference).
- Remote `origin` = `bentocodeing/kimi-seo` (this fork).
- Branch `main` tracks upstream untouched — a pristine mirror, fast-forward
  only. Never commit directly to it.
- Branch `kimi` = upstream + the Kimi rebrand and adaptations. All product
  work happens here; release tags are cut from `kimi`.

## Syncing from upstream

When upstream ships a new release:

1. **Pre-flight.** Be on `kimi` with a clean working tree, then
   `git fetch upstream --tags` and check how far behind:
   `git rev-list --count kimi..upstream/main`.
2. **Update the mirror.**
   ```bash
   git checkout main && git merge --ff-only upstream/main && git push origin main && git checkout kimi
   ```
   If `--ff-only` fails, `main` was polluted — stop and investigate; do not
   force.
3. **Merge into the product branch:** `git merge upstream/main --no-ff`.
   On conflicts, keep upstream's functional changes and the fork's naming
   per the invariants below. Hot spots: `README.md`, `install.sh`,
   `install.ps1`, `.claude-plugin/plugin.json`, `pyproject.toml`,
   `skills/*/SKILL.md` (description lines), `docs/`,
   `.github/workflows/ci.yml` (the fork triggers CI on `[main, kimi]`;
   upstream only has `main` — keep both entries), `.gitignore` (the fork
   drops the upstream `site/` ignore because `site/` hosts the Laravel
   website — never re-add it).
4. **Re-apply the rebrand invariants after ANY merge** (clean or not):
   scan the merge range for old branding and rebrand each hit except
   allowlisted attribution content.

The full runbook with exact commands, conflict-resolution rules, and abort
criteria: `.agents/skills/seo-upstream-sync/SKILL.md`.

## Rebrand invariants (surface, not plumbing)

- NEVER rename: skill dirs/files under `skills/` (all `seo-*`), agent files
  in `agents/`, script names in `scripts/`, internal paths.
- MUST stay renamed: config dir `~/.config/kimi-seo`, CLI `bin/kimi-seo`
  (and `kimi-seo run|setup|doctor` invocations), display brand "Kimi SEO",
  fork repo URLs `github.com/bentocodeing/kimi-seo`, env vars `KIMI_SEO_*`.
- MUST stay as-is (attribution): `github.com/AgriciDaniel/claude-seo` URLs,
  "forked from" notes, CHANGELOG.md history, CONTRIBUTORS.md, CITATION.cff,
  `skills/*/LICENSE.txt` upstream URLs.

## Verification gates (all must hold before any release step)

- `./bin/kimi-seo run check_rebrand.py` → PASS (exit 0). This includes the
  leaked-secret scan; if it fails, remove or relocate the offending content
  — never "fix" by allowlisting.
- `.venv/bin/python -m pytest tests/ -q` → green except the 2 known
  `tests/test_sync_flow.py` GitHub rate-limit failures.
- `./bin/kimi-seo run portability_check.py` → pass.

## Releasing

1. Bump the fork version on the fork's own semver line (not the upstream
   number) in `kimi.plugin.json`, `pyproject.toml`, and skill/agent
   metadata that carries it.
2. Update the `KIMI_SEO_TAG` default in `install.sh` (and `install.ps1`).
3. Prepend a mapped entry in the "Kimi SEO fork releases" section of
   CHANGELOG.md: "Kimi SEO vX.Y.Z — based on upstream claude-seo vA.B.C".
4. Tag from `kimi` and push:
   ```bash
   git tag -a vX.Y.Z -m "Kimi SEO vX.Y.Z — based on upstream claude-seo vA.B.C"
   git push origin kimi --tags
   ```

## Guardrails

- NEVER commit or push without explicit approval (the sync runbook's
  `push origin main` step excepted).
- NEVER force-push. NEVER rename skill/script/agent file names.
- If anything diverges from the runbook (non-ff main, dirty tree, unresolved
  conflicts), stop and report instead of improvising.
