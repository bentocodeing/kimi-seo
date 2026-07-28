# On-Page SEO Findings — kimi-seo.com

## What works

- **Titles**: unique on every page, descriptive, 20–47 chars — all inside the ~60-char display window. Pattern `{Section} — Kimi SEO Docs` is consistent and brand-carrying.
- **Headings**: exactly one `<h1>` per page, no skipped levels (h1→h2→h3 verified across all 11 pages). H1s are descriptive ("Commands Reference", "Migrating from kimi-seo v1.x to v2.0.0").
- **Indexability**: no meta robots / noindex anywhere; all pages return 200.
- **Images**: 15 `<img>` tags sitewide, **0 missing alt** — 100% alt coverage.
- **Internal linking**: docs pages carry 26–50 internal links each; docs hub links all 8 docs. No orphan pages detected.

## Findings

### 1. Identical meta description on all 11 pages — HIGH
Every page serves the same 107-character description: *"Kimi SEO is a free, open-source SEO analysis plugin for Kimi Code CLI: 25 skills, 18 subagents, 53 scripts."* Google rewrites or ignores duplicated descriptions, and the description is the highest-leverage SERP CTR element after the title. Docs pages should describe the doc ("Install the Kimi SEO plugin for Kimi Code CLI — requirements, setup, and first audit in 5 minutes.").
**Fix**: per-page `description` in the Blade templates; docs pages can derive it from the markdown's first paragraph or a frontmatter field.
**Falsifiability**: if unique descriptions ship and GSC CTR on docs queries does not move within 6 weeks at stable impressions, descriptions were not the CTR constraint.
**Leading indicator**: GSC CTR per page on non-brand queries.

### 2. No canonical link tags — MEDIUM
No `<link rel="canonical">` on any page. Risk is currently low (clean URL scheme, host canonicalization works, no parameters observed), but the docs content also lives in the GitHub repo and may be mirrored/scraped; canonicals are cheap insurance against duplicate-content ambiguity.
**Fix**: emit self-referencing canonicals in the base layout.

### 3. No Open Graph / Twitter Card meta — HIGH (distribution, not ranking)
Zero `og:*` / `twitter:*` tags sitewide. Every shared link (Twitter/X, Slack, Discord, LinkedIn — the actual channels where a CLI dev tool spreads) renders as a bare URL with no title, description, or image. For an open-source tool whose growth loop is developer word-of-mouth, this directly suppresses the link-sharing that generates the backlinks the domain currently lacks (see backlinks.md: absent from Common Crawl).
**Fix**: add `og:title`, `og:description`, `og:type` (website/article), `og:url`, `og:image` (generate a 1200×630 OG image — the suite's `seo image-gen` skill can produce one), plus `twitter:card: summary_large_image`.

### 4. Docs hub (`/docs`) is navigation-only — MEDIUM
133 words, h1 + link list, no introductory copy. It is the second-most-important page and currently gives crawlers and AI engines almost nothing to understand the docs corpus from.
**Fix**: add 200–400 words framing the documentation (what the suite does, who it's for, how the docs are organized) above the link list.

### 5. `/advertise` is thin (114 words) and indexable — LOW
Commercial contact page with minimal content. Either flesh it out (slot formats, audience stats, pricing model) or leave as-is — thin utility pages are normal. Not a quality risk at 1 page out of 11.
**Optional**: noindex `/login` and `/admin` (currently crawlable login form is harmless but noise in the index).

## Category score: 68/100
Titles, headings, alt text and internal linking are genuinely well done; the duplicated description, missing canonicals and missing social cards drag an otherwise-clean on-page layer down.
