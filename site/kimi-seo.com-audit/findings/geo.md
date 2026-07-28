# AI Search Readiness (GEO) Findings — kimi-seo.com

## AI crawler access

- `robots.txt` is `User-agent: * / Disallow:` — **GPTBot, ClaudeBot, PerplexityBot, Google-Extended and other AI crawlers are all allowed** (nothing blocked). Verdict: open, whether intentionally or by default.
- Server-rendered HTML, no JS wall, no login wall on content — AI crawlers see the full text.

## llms.txt

`https://kimi-seo.com/llms.txt` → **404**. An llms.txt giving AI engines a curated map (product summary, docs index, repo link, license) is a low-cost, increasingly-adopted convention — and thematically on-brand for this product.

## Citability assessment

**Strengths**: semantic HTML; one clear h1 and logical h2/h3 sections per doc; self-contained procedural sections (install steps, command references) that extract cleanly; code blocks with exact commands — the format LLMs prefer to quote.

**Gaps (MEDIUM)**:
1. **No structured data** — no `SoftwareApplication`/`TechArticle`/`Organization` to disambiguate the entity (see schema.md). AI engines must infer what "Kimi SEO" is from prose alone, and the name collides with the Kimi assistant brand — entity disambiguation matters unusually much here.
2. **No dates or authorship** on docs — freshness is a citation-selection signal.
3. **No factual "reference block"** on the homepage (version, license, platform, price) in a compact, quotable form.
4. **Zero third-party corroboration** — absent from Common Crawl; brand-mention signals are the weakest link (see backlinks.md).

## Brand mention signals

The strongest available mention channel for a dev tool (GitHub) is linked but not leveraged on-site: no star/fork counts, no release badges, no link to discussions/issues as community proof. `sameAs` schema + visible repo stats would connect the entity graph.

## Recommendations

1. **(MEDIUM)** Create `/llms.txt`: product one-liner, docs map with one-line descriptions, repo URLs, license. 30 minutes of work.
2. **(HIGH, shared with schema.md)** Ship the JSON-LD `@graph` — entity clarity is the precondition for both rich results and correct AI attribution (Kimi SEO the plugin vs. Kimi the assistant).
3. **(LOW)** Add a "Facts" block to the homepage or footer: current version, license (MIT), platform (Kimi Code CLI), price (free) — quotable in one glance.
4. **(LOW, strategic)** Encourage citations where the audience actually is: awesome-list PRs, Kimi Code community, dev.to/HN launch posts — the only durable fix for the mention gap.

**Falsifiability**: if llms.txt + schema ship and AI-referral/mention checks (e.g. asking ChatGPT/Perplexity "what is kimi-seo") still misattribute or omit the product after a month, the constraint is external mentions, not on-site signals — pivot to recommendation 4.
**Leading indicator**: periodic manual probe — "Install kimi-seo" answers in 2–3 AI engines; watch for correct description + URL.

## Category score: 55/100
Crawler access and extractable content are genuinely good; entity disambiguation, freshness, and corroboration are all missing.
