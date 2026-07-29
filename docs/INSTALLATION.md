# Installation Guide

> This guide covers the Kimi fork. Upstream project: [`AgriciDaniel/claude-seo`](https://github.com/AgriciDaniel/claude-seo).
>
> **No API keys or accounts are needed** for your first audit — every
> credential is optional. New here? [Getting Started](GETTING-STARTED.md)
> is the faster entry point; this page is the detailed reference.

## Prerequisites

- **Kimi Code CLI** installed and configured
- **Python 3.10+** with pip (used by the one-time `setup` step)
- **Git** — only for the manual install method

Optional:
- **Playwright Chromium** — provisioned automatically by `setup`; failure is non-fatal; needed only for SPA rendering and screenshots

## Choose your method

| | Plugin install (recommended) | Manual install |
|---|---|---|
| How | Kimi Code's plugin manager, one command | `git clone` + installer script |
| Where it lands | `~/.kimi-code/plugins/managed/kimi-seo/` | `~/.kimi-code/skills/` + `~/.agents/agents/` |
| Update | Repeat the install command | Re-run the installer |
| Uninstall | `/plugins remove kimi-seo` | `uninstall.sh` |
| Best for | Everyone on Kimi Code | Inspecting/hacking the repo, offline mirrors, other harnesses |

Both methods give you the same 25 skills and the same `/kimi-seo:seo`
commands. Pick one — don't run both.

## Method 1 — Plugin install (recommended)

### Install

Inside Kimi Code:

```
/plugins install https://github.com/bentocodeing/kimi-seo
/reload
/kimi-seo:seo setup
```

Three things happen:

1. `/plugins install` copies the repository into Kimi Code's managed plugins
   directory (`~/.kimi-code/plugins/managed/kimi-seo/`) and reads
   `kimi.plugin.json` from the plugin root: the `skills/` and
   `plugin-skills/` skill roots, the session-start orientation skill
   (`kimi-seo-runtime`), and the schema validation hook (`PostToolUse` on
   `Edit|Write`). Since this is a third-party source, Kimi Code asks you to
   confirm you trust it — that's expected.
2. `/reload` activates the plugin in the current session (a new session
   works too).
3. `/kimi-seo:seo setup` is an explicit, one-time provisioning step that
   builds the isolated Python runtime (venv + Chromium) in Kimi SEO's
   persistent data directory. Nothing is installed globally.

To pin a specific release instead of tracking the latest, use a tag URL:
`/plugins install https://github.com/bentocodeing/kimi-seo/releases/tag/<tag>`.

### Verify

```
/plugins list            # kimi-seo appears in the list
/plugins info kimi-seo   # manifest diagnostics — should be clean
/kimi-seo:seo doctor     # read-only runtime health check
```

Then confirm the skill responds:

```
/kimi-seo:seo
```

You should see a help message or a prompt for a URL.

### Update

Kimi Code plugins do not auto-update. To move to the latest version, repeat
the installation — the manager replaces the managed copy:

```
/plugins install https://github.com/bentocodeing/kimi-seo
/reload
/kimi-seo:seo setup    # refreshes the runtime if scripts changed
```

`/kimi-seo:seo doctor` tells you afterwards whether anything needs attention.

### Uninstall

```
/plugins remove kimi-seo
/reload
```

(or open `/plugins`, select kimi-seo in the **Installed** tab, press `D`.)

`remove` deletes the installation record. For a full cleanup, also delete
the managed copy and, if you want, your credentials:

```bash
rm -rf ~/.kimi-code/plugins/managed/kimi-seo/
rm -rf ~/.config/kimi-seo/   # optional: API credentials and config
```

## Method 2 — Manual install

`install.sh` copies each `skills/seo*` directory into `~/.kimi-code/skills/`
and each `agents/*.md` into `~/.agents/agents/`, where Kimi Code discovers
them without the plugin manager. Runtime files (scripts, schema templates,
`bin/kimi-seo`) land under `~/.kimi-code/skills/seo/`.

### Unix / macOS / Linux

```bash
git clone --depth 1 --branch kimi https://github.com/bentocodeing/kimi-seo.git
bash kimi-seo/install.sh
```

Review-then-run alternative:

```bash
curl -fsSL https://raw.githubusercontent.com/bentocodeing/kimi-seo/kimi/install.sh > install.sh
cat install.sh        # review
bash install.sh       # run when satisfied
rm install.sh
```

### Windows (PowerShell)

```powershell
git clone --depth 1 --branch kimi https://github.com/bentocodeing/kimi-seo.git
powershell -ExecutionPolicy Bypass -File kimi-seo\install.ps1
```

The Windows path uses `git clone` rather than `irm | iex` because Kimi Code's
own security guardrails flag piped remote-script execution. Inspect
`install.ps1` before running.

### What the installer does

The installer delegates dependency and Chromium provisioning to the same
managed runtime the skills use. It creates
`~/.kimi-code/skills/seo/.venv/` and never falls back to global or user
package installation. If only Chromium fails, the installer reports a
degraded result and raw-fetch analysis remains available.

| Component | Path |
|-----------|------|
| Main skill | `~/.kimi-code/skills/seo/` |
| Sub-skills | `~/.kimi-code/skills/seo-*/` |
| Subagents | `~/.agents/agents/seo-*.md` |
| Runtime launcher | `~/.kimi-code/skills/seo/bin/kimi-seo` |
| Isolated Python | `~/.kimi-code/skills/seo/.venv/` |

### Verify

```bash
ls ~/.kimi-code/skills/seo/SKILL.md
~/.kimi-code/skills/seo/bin/kimi-seo doctor
```

Then start Kimi Code (`kimi`) and type `/kimi-seo:seo` — you should see a
help message or a prompt for a URL.

### Update

Re-run the installer from a fresh clone — it overwrites the installed files:

```bash
git clone --depth 1 --branch kimi https://github.com/bentocodeing/kimi-seo.git
bash kimi-seo/install.sh
```

For a clean slate (removes stale files from older versions), run the
uninstaller first, then the installer.

### Uninstall

```bash
git clone --depth 1 --branch kimi https://github.com/bentocodeing/kimi-seo.git
bash kimi-seo/uninstall.sh
```

`uninstall.sh` removes all installed sub-skills, sub-agents, and the MCP
entries from the Kimi Code MCP settings file. Do not maintain a hand-coded
`rm` list — the shipped uninstaller is the canonical source.

## Troubleshooting

### "Skill not found" error

- Plugin install: check `/plugins list` — if kimi-seo is missing or shows a
  diagnostic, run `/plugins info kimi-seo` for details, then reinstall.
- Manual install: check `ls ~/.kimi-code/skills/seo/SKILL.md` — if the file
  doesn't exist, re-run the installer.
- In both cases, run `/reload` (or start a new session) after installing:
  the current session does not pick up plugin changes by itself.

### Python dependency errors

- Plugin install: `/kimi-seo:seo setup`
- Manual install: `~/.kimi-code/skills/seo/bin/kimi-seo setup`

### Playwright screenshot errors

Run `setup` again (path depends on your install method, see above), then
check `doctor`. Chromium failures are non-fatal: raw-fetch analysis keeps
working.

### Permission errors on Unix (manual install)

Make sure scripts are executable:

```bash
chmod +x ~/.kimi-code/skills/seo/scripts/*.py
```
