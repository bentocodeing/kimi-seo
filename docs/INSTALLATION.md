# Installation Guide

> This guide covers the Kimi fork. Upstream project: [`AgriciDaniel/claude-seo`](https://github.com/AgriciDaniel/claude-seo).
>
> **No API keys or accounts are needed** for your first audit — every
> credential is optional. New here? [Getting Started](GETTING-STARTED.md)
> is the faster entry point; this page is the detailed reference.

## Prerequisites

- **Python 3.10+** with pip
- **Git** for cloning the repository
- **Kimi Code CLI** installed and configured

Optional:
- **Playwright Chromium** - install.sh attempts this automatically; failure is non-fatal; needed only for SPA rendering and screenshots

## Quick Install

### Plugin Install (Kimi Code, recommended)

Inside Kimi Code:

```
/plugins install https://github.com/bentocodeing/kimi-seo
/reload
/kimi-seo:seo setup
```

Kimi Code copies the repository into its managed plugins directory
(`~/.kimi-code/plugins/managed/kimi-seo/`) and reads `kimi.plugin.json` from
the plugin root: the `skills/` and `plugin-skills/` skill roots, the
session-start orientation skill (`kimi-seo-runtime`), and the schema
validation hook (`PostToolUse` on `Edit|Write`). Hook commands run with the
plugin root as working directory.

`/kimi-seo:seo setup` is an explicit, one-time provisioning step that writes the
virtual environment and browser to Kimi SEO's persistent data directory. Use
`/kimi-seo:seo doctor` for a read-only check.

### Manual skills/agents layout (Kimi Code, no plugin manager)

`install.sh` (default target) copies each `skills/seo*` directory into
`~/.kimi-code/skills/` and each `agents/*.md` into `~/.agents/agents/`, where
Kimi Code discovers them without the plugin manager. Runtime files (scripts,
schema templates, `bin/kimi-seo`) land under `~/.kimi-code/skills/seo/`.

### Manual Install (Unix, macOS, Linux)

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

### Manual Install (Windows, PowerShell)

```powershell
git clone --depth 1 --branch kimi https://github.com/bentocodeing/kimi-seo.git
powershell -ExecutionPolicy Bypass -File kimi-seo\install.ps1
```

The Windows path uses `git clone` rather than `irm | iex` because Kimi Code's own security guardrails flag piped remote-script execution. Inspect `install.ps1` before running.

## Manual Installation

1. **Clone the repository**

```bash
git clone --branch kimi https://github.com/bentocodeing/kimi-seo.git
cd kimi-seo
```

2. **Run the installer**

```bash
./install.sh
```

3. **Verify the managed runtime**

The installer delegates dependency and Chromium provisioning to the same runtime
used by every skill. It creates `~/.kimi-code/skills/seo/.venv/` and never falls
back to global or user package installation.

```bash
~/.kimi-code/skills/seo/bin/kimi-seo doctor
```

If core setup failed, rerun the inspected installer. If only Chromium failed,
the installer reports a degraded result and raw-fetch analysis remains available.

## Installation Paths

With the default Kimi target, the installer copies files to:

| Component | Path |
|-----------|------|
| Main skill | `~/.kimi-code/skills/seo/` |
| Sub-skills | `~/.kimi-code/skills/seo-*/` |
| Subagents | `~/.agents/agents/seo-*.md` |
| Runtime launcher | `~/.kimi-code/skills/seo/bin/kimi-seo` |
| Isolated Python | `~/.kimi-code/skills/seo/.venv/` |

## Verify Installation

1. Start Kimi Code:

```bash
kimi
```

2. Check that the skill is loaded:

```
/kimi-seo:seo
```

You should see a help message or prompt for a URL.

## Uninstallation

If installed as a plugin, uninstall it through the Kimi Code plugin manager (see `/plugins`).

If installed manually, run the uninstaller from a fresh clone:

```bash
git clone --depth 1 --branch kimi https://github.com/bentocodeing/kimi-seo.git
bash kimi-seo/uninstall.sh
```

`uninstall.sh` removes all installed sub-skills, sub-agents, and the plugin's MCP entries from the Kimi Code MCP settings file. Do not maintain a hand-coded `rm` list. The shipped uninstaller is the canonical source.

## Upgrading

To upgrade to the latest version:

Caution: Prefer downloading, inspecting, then running remote scripts; the pipe-to-shell form below is the less-safe convenience option.

```bash
# Uninstall current version
curl -fsSL https://raw.githubusercontent.com/bentocodeing/kimi-seo/kimi/uninstall.sh | bash

# Install new version
curl -fsSL https://raw.githubusercontent.com/bentocodeing/kimi-seo/kimi/install.sh | bash
```

## Troubleshooting

### "Skill not found" error

Ensure the skill is installed in the correct location:

```bash
ls ~/.kimi-code/skills/seo/SKILL.md
```

If the file doesn't exist, re-run the installer.

### Python dependency errors

Run the managed setup again:

```bash
~/.kimi-code/skills/seo/bin/kimi-seo setup
```

### Playwright screenshot errors

Run the managed setup again and inspect the result:

```bash
~/.kimi-code/skills/seo/bin/kimi-seo setup
~/.kimi-code/skills/seo/bin/kimi-seo doctor
```

### Permission errors on Unix

Make sure scripts are executable:

```bash
chmod +x ~/.kimi-code/skills/seo/scripts/*.py
```
