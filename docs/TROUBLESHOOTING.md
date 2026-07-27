# Troubleshooting

## Common Issues

### Skill Not Loading

**Symptom:** `/seo` command not recognized

**Solutions:**

For plugin installs, verify and reinstall through Kimi Code:
```
/plugins install https://github.com/bentocodeing/kimi-seo
/reload
```

For manual installs:

1. Verify installation:
```bash
ls ~/.kimi-code/skills/seo/SKILL.md
```

2. Check SKILL.md has proper frontmatter:
```bash
head -5 ~/.kimi-code/skills/seo/SKILL.md
```
Should start with `---` followed by YAML.

3. Restart Kimi Code:
```bash
kimi
```

4. Re-run installer:

Caution: Prefer downloading, inspecting, then running remote scripts; the pipe-to-shell form below is the less-safe convenience option.

```bash
curl -fsSL https://raw.githubusercontent.com/bentocodeing/kimi-seo/kimi/install.sh | bash
```

---

### Python Dependency Errors

**Symptom:** `ModuleNotFoundError: No module named 'requests'`

**Solution:**

Dependencies belong in the managed runtime. For a plugin install, run:

```bash
/seo doctor
/seo setup
```

For a manual install, run:
```bash
~/.kimi-code/skills/seo/bin/kimi-seo doctor
~/.kimi-code/skills/seo/bin/kimi-seo setup
```

Do not install individual packages, use `pip --user`, or create a PATH shim.

### requirements.txt Not Found

**Symptom:** `No such file: requirements.txt` after install

**Solution:** For plugin installs, reinstall the plugin first:

```
/plugins install https://github.com/bentocodeing/kimi-seo
/reload
```

For manual installs, requirements.txt is copied to the skill directory:

```bash
ls ~/.kimi-code/skills/seo/requirements.txt
```

If missing, download it directly:
```bash
curl -fsSL https://raw.githubusercontent.com/bentocodeing/kimi-seo/kimi/requirements.txt \
  -o ~/.kimi-code/skills/seo/requirements.txt
```

### Windows Python Detection Issues

**Symptom:** `python is not recognized` or `pip points to wrong Python`

**Solution (v1.2.0+):** The Windows installer now tries both `python` and `py -3`. If both fail:

1. Install Python from [python.org](https://python.org) and check "Add to PATH"
2. Rerun `install.ps1`; it resolves `py -3`, `python3`, then `python`
3. Run `/seo doctor` after installation

---

### Playwright Screenshot Errors

**Symptom:** `playwright._impl._errors.Error: Executable doesn't exist`

**Solution:** rerun managed setup so the browser is installed through the same
interpreter and persistent browser directory:
```bash
/seo setup
/seo doctor
```

---

### Permission Denied Errors

**Symptom:** `Permission denied` when running scripts

**Solution:**
```bash
chmod +x ~/.kimi-code/skills/seo/scripts/*.py
```

---

---

### Subagent Not Found

**Symptom:** `Agent 'seo-technical' not found`

**Solution:**

For plugin installs, check the installed plugin list and reinstall via
`/plugins install https://github.com/bentocodeing/kimi-seo` followed by
`/reload`; subagents load from the plugin, not `~/.agents/agents/`.

For manual installs:

1. Verify agent files exist:
```bash
ls ~/.agents/agents/seo-*.md
```

2. Check agent frontmatter:
```bash
head -5 ~/.agents/agents/seo-technical.md
```

3. Re-install agents:
```bash
cp /path/to/kimi-seo/agents/*.md ~/.agents/agents/
```

---

### Timeout Errors

**Symptom:** `Request timed out after 30 seconds`

**Solutions:**

1. The target site may be slow: try again
2. Increase timeout in script calls
3. Check your network connection
4. Some sites block automated requests

---

### Schema Validation False Positives

**Symptom:** Hook blocks valid schema

**Check:**

1. Ensure placeholders are replaced
2. Verify @context is `https://schema.org`
3. Check for deprecated/retired types: HowTo and SpecialAnnouncement, plus the June 2025 retirements (ClaimReview, VehicleListing, EstimatedSalary, LearningVideo, and the CourseInfo carousel)
4. FAQPage rich results were retired for all sites on 2026-05-07. The hook does not block it because it remains a valid Schema.org type, but no AI or ranking benefit is confirmed.
5. Validate at [Google's Rich Results Test](https://search.google.com/test/rich-results)

---

### Slow Audit Performance

**Symptom:** Full audit takes too long

**Solutions:**

1. Audit crawls up to 500 pages: large sites take time
2. Subagents run in parallel to speed up analysis
3. For faster checks, use `/seo page` on specific URLs
4. Check if site has slow response times

---

## Getting Help

1. **Check the docs:** Review [COMMANDS.md](COMMANDS.md) and [ARCHITECTURE.md](ARCHITECTURE.md)

2. **GitHub Issues:** Report bugs at the repository

3. **Logs:** Check Kimi Code's output for error details

## Debug Mode

To see detailed output, check Kimi Code's internal logs or run scripts directly:

```bash
# Test fetch
python3 ~/.kimi-code/skills/seo/scripts/fetch_page.py https://example.com

# Test parse
python3 ~/.kimi-code/skills/seo/scripts/parse_html.py page.html --json

# Test screenshot
python3 ~/.kimi-code/skills/seo/scripts/capture_screenshot.py https://example.com
```
