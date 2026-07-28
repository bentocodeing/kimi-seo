# Getting Started with Kimi SEO

> The 10-minute path from install to your first actionable audit. No API
> keys, no accounts, no prior SEO tooling required.
> Upstream project: [`AgriciDaniel/claude-seo`](https://github.com/AgriciDaniel/claude-seo).

## 1. Install

Inside Kimi Code:

```
/plugins install https://github.com/bentocodeing/kimi-seo
/reload
/kimi-seo:seo setup
```

`setup` is a one-time step that provisions the isolated Python runtime the
scripts run in. Check everything is ready anytime with `/kimi-seo:seo doctor`
(read-only, changes nothing).

Manual install, Windows, and uninstall: [Installation Guide](INSTALLATION.md).

## 2. Run your first audit

```
/kimi-seo:seo audit https://your-site.com
```

Or just say "audit your-site.com" — the orchestrator routes natural language
too. The audit crawls up to 500 pages and writes its deliverables to a
`{domain}-audit/` folder in your current project directory:

| File | What it is |
|------|-----------|
| `FULL-AUDIT-REPORT.md` | Every finding, grouped by category, with a 0-100 health score |
| `ACTION-PLAN.md` | **Start here.** Prioritized fixes: Critical → High → Medium → Low |
| `findings/*.md` | One file per specialist (technical, content, schema, …) |
| `audit-data.json` | Structured data used for PDF report generation |
| `screenshots/` | Desktop + mobile captures |

## 3. Understand what just ran (and what didn't)

The full audit is a bundle of specialists — it runs **most** of the
analysis commands for you:

- **Always included:** technical SEO, content quality (E-E-A-T), schema,
  sitemap, performance (Core Web Vitals), visual, AI-search readiness (GEO),
  search experience (SXO).
- **Included when relevant:** local SEO and maps (local businesses),
  e-commerce (product sites), backlinks, clustering (content sites), drift
  (if a baseline exists), Google field data (if credentials are configured).

The other commands are **standalone tools**, not part of the audit:
`page` (deep single-URL dive), `content-brief`, `plan`, `hreflang`,
`programmatic`, `competitor-pages`, `flow`, and the paid extensions. Run them
individually when you need them — see the [Commands Reference](COMMANDS.md).

## 4. Fix the issues (there is no auto-fix button)

Kimi SEO **diagnoses and generates — it never modifies your website**. The
fix loop looks like this:

1. **Read `ACTION-PLAN.md`.** Critical = blocks indexing or risks penalties
   (fix now). High = hurts rankings (this week). Medium = this month.
   Low = backlog.
2. **Apply the fix.** Two ways:
   - Fix it yourself in your site/CMS. Many commands hand you ready-to-paste
     output: corrected JSON-LD (`schema`), a sitemap file (`sitemap
     generate`), hreflang tags (`hreflang`), a rewrite brief
     (`content-brief`).
   - If your site's codebase is open in Kimi Code, just ask the agent to
     apply the Critical/High fixes directly — it can edit templates, meta
     tags, `robots.txt`, and schema for you.
3. **Re-check the category you fixed** with the narrow command instead of a
   full re-audit: `/kimi-seo:seo technical <url>`, `/kimi-seo:seo schema
   <url>`, `/kimi-seo:seo page <url>`, …
4. **Verify over time with drift monitoring:**

   ```
   /kimi-seo:seo drift baseline <url>   # snapshot current state
   /kimi-seo:seo drift compare <url>    # after fixes: what actually changed
   ```

## 5. API keys: none required

The audit above ran with **zero credentials**. Every key is optional and
only unlocks an enrichment layer — add them later, one at a time:

| Credential | Adds | Without it |
|-----------|------|-----------|
| Google API key (free) | PageSpeed lab data, CrUX field data | CWV falls back to lab estimates |
| Google OAuth | Search Console indexation, clicks/impressions | Indexation inferred from page signals |
| GA4 config | Organic traffic trends | No traffic charts |
| Moz / Bing keys | Detailed backlink profile | Common Crawl domain-level metrics |
| DataForSEO, Ahrefs, Profound, SE Ranking accounts | Paid extension data | Extensions simply unused |

Setup wizard: `/kimi-seo:seo google setup`. Credentials live in
`~/.config/kimi-seo/` with owner-only permissions; nothing is committed
anywhere.

## Where to go next

- [Commands Reference](COMMANDS.md) — every command in depth
- [Troubleshooting](TROUBLESHOOTING.md) — install and runtime issues
