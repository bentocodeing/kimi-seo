# Sitemap Findings — kimi-seo.com

## Status: NO XML SITEMAP EXISTS (HIGH)

- `https://kimi-seo.com/sitemap.xml` → **404**
- `https://kimi-seo.com/sitemap_index.xml` → **404**
- `robots.txt` contains no `Sitemap:` directive (body is exactly `User-agent: *\nDisallow:`).

## Inventory (manually crawled — the site is small enough to enumerate)

11 indexable routes, all HTTP 200:

| Route | Type | Priority hint |
|---|---|---|
| `/` | Landing | 1.0 |
| `/docs` | Docs hub | 0.9 |
| `/docs/getting-started` | Doc | 0.9 (largest page, 5,137 words, primary entry) |
| `/docs/commands` | Doc | 0.8 (reference, 3,428 words) |
| `/docs/installation` | Doc | 0.8 |
| `/docs/architecture` | Doc | 0.7 |
| `/docs/mcp-integration` | Doc | 0.7 |
| `/docs/migration-v1-to-v2` | Doc | 0.6 |
| `/docs/troubleshooting` | Doc | 0.6 |
| `/docs/workflow` | Doc | 0.5 |
| `/advertise` | Commercial | 0.3 |

Excluded: `/admin`, `/login` (auth surfaces — keep out, consider noindex), `/media/*` (asset route, whitelist-enforced, 404s correctly for non-whitelisted paths).

## Quality gates

- Site has **0 location pages** — the 30-page warning / 50-page hard-stop gates do not apply.
- No pagination, no faceted URLs, no parameter URLs observed — no index-bloat risk today.
- Docs are single-language (en) — no hreflang sitemap entries needed.

## Recommendation

1. Generate `/sitemap.xml` (static file or `spatie/laravel-sitemap` route) covering the 11 routes above with `<lastmod>` from real content mtimes.
2. Add `Sitemap: https://kimi-seo.com/sitemap.xml` as the last line of `robots.txt`.
3. Submit the sitemap in Google Search Console and Bing Webmaster Tools; optionally ping IndexNow (the site is static-ish and changes rarely — IndexNow is low-cost here).
4. Regenerate on deploy (the docs set changes with repo releases).

**Falsifiability**: if all 11 URLs are "Submitted and indexed" in GSC within 2 weeks, discovery was the constraint. If docs pages remain unindexed, the constraint is authority/links, not discovery — shift effort to backlinks.
**Leading indicator**: GSC Pages report, "Submitted and indexed" count vs. 11.

## Category verdict

Absence confirmed and material (see technical.md §1: domain is invisible to Common Crawl). Score folded into Technical SEO category.
