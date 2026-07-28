# Backlink Profile Findings — kimi-seo.com

**Data source**: Common Crawl Web Graph (cc-main-2026-jan-feb-mar) via `commoncrawl_graph.py` — the only free source available (no Moz/Bing/DataForSEO credentials configured).

## Result: domain not present in the link graph

```json
{ "in_crawl": false, "in_rankings": false }
```

kimi-seo.com does not appear in Common Crawl's web graph at all — no page-level or domain-level rank, no harmonic centrality, no measurable referring-domain data. Interpretation: the domain is too new and/or too lightly linked to register in the largest public crawl. **Effective referring-domain count: ~0.** Toxic-link analysis is moot — there is no profile to be toxic.

## Why this is the strategic bottleneck (CONNECT-system)

Every on-page fix in this audit (sitemap, schema, descriptions) improves how the site is *understood once crawled*. None of them create *authority*. For an 11-page site on a fresh domain, rankings for anything beyond the brand name are gated by external links — the one thing the site cannot give itself. This finding gates Phase 3 of the action plan.

## Recommendations

1. **(HIGH, ongoing)** Dev-tool link acquisition in order of effort/impact:
   - GitHub repo ↔ site bidirectional linking is already in place — good; ensure the repo's "Website" field points to kimi-seo.com.
   - Awesome-list and tool-directory submissions (awesome-CLI, awesome-SEO, Kimi Code community lists).
   - Launch posts: dev.to, Hacker News (Show HN), Reddit r/SEO, r/commandline — each is a citation + referral source.
   - The `/advertise` page implies traffic ambitions; links come before ad sales.
2. **(MEDIUM)** Configure free Moz API (2,500 rows/month) in `~/.config/kimi-seo/` and re-run `kimi-seo run backlinks_auth.py --check` for DA tracking over time; Common Crawl will pick the domain up within a few quarterly crawls once real links exist.
3. **(LOW)** After links land, re-run this check quarterly (`commoncrawl_graph.py kimi-seo.com`) — `in_rankings: true` is the binary proof the graph noticed.

**Falsifiability**: if 10+ genuine referring domains exist (Moz/GSC) but Common Crawl still shows `in_crawl: false` after two crawl cycles, the metric is lagging, not the links — rely on GSC Links report instead.
**Leading indicator**: GSC "Links → External links → Referring domains" count, monthly.

## Category verdict

No score impact (not an on-page category in the weighted model), but flagged in the executive summary as the primary growth constraint.
