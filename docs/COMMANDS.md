# Commands Reference

## Overview

All Kimi SEO commands start with `/kimi-seo:seo` followed by a subcommand.

**Invocation in Kimi Code:** `/kimi-seo:seo ...` is documentation shorthand. In the CLI,
use the plugin slash command `/kimi-seo:seo <subcommand> [args]` or
`/skill:seo <subcommand> [args]`. Analysis commands write their artifacts
(`{domain}-audit/` reports, action plan, screenshots) into your current
working directory — never into the plugin installation.

## Command List

### `/kimi-seo:seo setup`

Explicitly create or refresh the isolated Python runtime and Playwright Chromium.
This is required once after a marketplace plugin install. Manual installers run
the same setup automatically. It never installs packages globally.

### `/kimi-seo:seo doctor`

Check runtime, dependency, and Chromium readiness without changing the system.
Diagnostic output omits absolute paths and environment values.

### `/kimi-seo:seo audit <url>`

Full website SEO audit with parallel analysis.

**Example:**
```
/kimi-seo:seo audit https://example.com
```

**What it does:**
1. Crawls up to 500 pages
2. Detects business type
3. Delegates to up to 15 specialist subagents in parallel (8 always-on + 7 conditional)
4. Generates SEO Health Score (0-100)
5. Creates prioritized action plan

**Output:**
- `FULL-AUDIT-REPORT.md`
- `ACTION-PLAN.md`
- `screenshots/` (if Playwright available)

---

### `/kimi-seo:seo page <url>`

Deep single-page analysis.

**Example:**
```
/kimi-seo:seo page https://example.com/about
```

**What it analyzes:**
- On-page SEO (title, meta, headings, URLs)
- Content quality (word count, readability, E-E-A-T)
- Technical elements (canonical, robots, Open Graph)
- Schema markup
- Images (alt text, sizes, formats)
- Core Web Vitals potential issues

---

### `/kimi-seo:seo technical <url>`

Technical SEO audit across 9 categories.

**Example:**
```
/kimi-seo:seo technical https://example.com
```

**Categories:**
1. Crawlability
2. Indexability
3. Security
4. URL Structure
5. Mobile Optimization
6. Core Web Vitals (LCP, INP, CLS)
7. Structured Data
8. JavaScript Rendering
9. IndexNow Protocol

---

### `/kimi-seo:seo content <url>`

E-E-A-T and content quality analysis.

**Example:**
```
/kimi-seo:seo content https://example.com/blog/post
```

**What it evaluates:**
- Experience signals (first-hand knowledge)
- Expertise (author credentials)
- Authoritativeness (external recognition)
- Trustworthiness (transparency, security)
- AI citation readiness
- Content freshness

---

### `/kimi-seo:seo content-brief <topic or url>`

Generate a detailed SEO content brief: target keywords, search intent, heading outline, internal link targets, and competitor angle.

**Example:**
```
/kimi-seo:seo content-brief "best running shoes for flat feet"
```

**What it produces:**
- Primary and secondary target keywords
- Search intent and audience
- Section-by-section heading outline
- Internal link recommendations
- Competitor content angles to beat

---

### `/kimi-seo:seo schema <url>`

Schema markup detection, validation, and generation.

**Example:**
```
/kimi-seo:seo schema https://example.com
```

**What it does:**
- Detects existing schema (JSON-LD, Microdata, RDFa)
- Validates against Google's requirements
- Identifies missing opportunities
- Generates ready-to-use JSON-LD

---

### `/kimi-seo:seo geo <url>`

AI Overviews / Generative Engine Optimization.

**Example:**
```
/kimi-seo:seo geo https://example.com/blog/guide
```

**What it analyzes:**
- Citability score (quotable facts, statistics)
- Structural readability (headings, lists, tables)
- Entity clarity (definitions, context)
- Authority signals (credentials, sources)
- Structured data support

---

### `/kimi-seo:seo images <url>`

Image optimization analysis. Subcommands: `serp <keyword>` (image SERP / visual-search analysis), `optimize <path>` (local file optimization + IPTC AI labeling).

**Examples:**
```
/kimi-seo:seo images https://example.com
/kimi-seo:seo images serp "running shoes"
/kimi-seo:seo images optimize ./hero.webp
```

**What it checks:**
- Alt text presence and quality
- File sizes (flag >200KB)
- Formats (WebP/AVIF recommendations)
- Responsive images (srcset, sizes)
- Lazy loading
- CLS prevention (dimensions)

---

### `/kimi-seo:seo sitemap <url>`

Analyze existing XML sitemap.

**Example:**
```
/kimi-seo:seo sitemap https://example.com/sitemap.xml
```

**What it validates:**
- XML format
- URL count (<50k per file)
- URL status codes
- lastmod accuracy
- Deprecated tags (priority, changefreq)
- Coverage vs crawled pages

---

### `/kimi-seo:seo sitemap generate`

Generate new sitemap with industry templates.

**Example:**
```
/kimi-seo:seo sitemap generate
```

**Process:**
1. Select or auto-detect business type
2. Interactive structure planning
3. Apply quality gates (30/50 location page limits)
4. Generate valid XML
5. Create documentation

---

### `/kimi-seo:seo plan <type>`

Strategic SEO planning.

**Types:** `saas`, `local`, `ecommerce`, `publisher`, `agency`

**Example:**
```
/kimi-seo:seo plan saas
```

**What it creates:**
- Complete SEO strategy
- Competitive analysis
- Content calendar
- Implementation roadmap (4 phases)
- Site architecture design

---

### `/kimi-seo:seo competitor-pages [url|generate]`

Competitor comparison page generation.

**Examples:**
```
/kimi-seo:seo competitor-pages https://example.com/vs/competitor
/kimi-seo:seo competitor-pages generate
```

**Capabilities:**
- Generate "X vs Y" comparison page layouts
- Create "Alternatives to X" page structures
- Build feature comparison matrices with scoring
- Generate Product + AggregateRating schema markup
- Apply conversion-optimized CTA placement
- Enforce fairness guidelines (accurate data, source citations)

---

### `/kimi-seo:seo hreflang [url]`

Hreflang and international SEO audit and generation. Subcommand: `audit <directory-or-url>` (audit hreflang across a local build directory or a live URL set).

**Examples:**
```
/kimi-seo:seo hreflang https://example.com
/kimi-seo:seo hreflang audit ./dist
```

**Capabilities:**
- Validate self-referencing hreflang tags
- Check return tag reciprocity (A→B requires B→A)
- Verify x-default tag presence
- Validate ISO 639-1 language and ISO 3166-1 region codes
- Check canonical URL alignment with hreflang
- Detect protocol mismatches (HTTP vs HTTPS)
- Generate correct hreflang link tags and sitemap XML

---

### `/kimi-seo:seo programmatic [url|plan]`

Programmatic SEO analysis and planning for pages generated at scale.

**Examples:**
```
/kimi-seo:seo programmatic https://example.com/tools/
/kimi-seo:seo programmatic plan
```

**Capabilities:**
- Assess data source quality (CSV, JSON, API, database)
- Plan template engines with unique content per page
- Design URL pattern strategies (`/tools/[tool-name]`, `/[city]/[service]`)
- Automate internal linking (hub/spoke, related items, breadcrumbs)
- Enforce thin content safeguards (quality gates, word count thresholds)
- Prevent index bloat (noindex low-value, pagination, faceted nav)

---

### `/kimi-seo:seo local <url>`

Local SEO analysis covering Google Business Profile, citations, reviews, and the map pack.

**Example:**
```
/kimi-seo:seo local https://example.com
```

**What it analyzes:**
- Google Business Profile signals (categories, hours, photos, posts)
- NAP (Name, Address, Phone) consistency across the page and external citations
- Review velocity, response rate, and sentiment
- Local schema markup (LocalBusiness, Restaurant, Service-specific types)
- Industry-specific local factors (brick-and-mortar, SAB, hybrid)
- Map pack visibility signals

---

### `/kimi-seo:seo maps [command] [args]`

Maps intelligence: geo-grid rank tracking, GBP profile audits, review intelligence, cross-platform NAP verification, competitor radius mapping.

**Examples:**
```
/kimi-seo:seo maps "Joe's Coffee" "austin tx"
/kimi-seo:seo maps grid "coffee shop" "austin tx"
/kimi-seo:seo maps gbp "Joe's Coffee" "austin tx"
/kimi-seo:seo maps reviews "Joe's Coffee" "austin tx"
/kimi-seo:seo maps competitors "auto repair" "denver"
/kimi-seo:seo maps nap "Joe's Coffee" "austin tx"
/kimi-seo:seo maps schema "Joe's Coffee" "austin tx"
```

**Capabilities:**
- Rank tracking on a geographic grid (typically 49 points)
- GBP profile audit with completeness scoring
- Review aggregation across Google, Yelp, Facebook, Bing
- Competitor discovery within a configurable radius

---

### `/kimi-seo:seo backlinks <url>`

Backlink profile analysis with a 3-tier data cascade: free (Common Crawl + verification), free with signup (Moz, Bing Webmaster Tools), paid (DataForSEO).

**Examples:**
```
/kimi-seo:seo backlinks https://example.com
/kimi-seo:seo backlinks gap https://example.com https://competitor.com
/kimi-seo:seo backlinks toxic https://example.com
/kimi-seo:seo backlinks new https://example.com
/kimi-seo:seo backlinks verify https://example.com --links known-links.txt
/kimi-seo:seo backlinks setup
```

**What it analyzes:**
- Domain Authority and Page Authority (Moz)
- Referring domain count and growth
- Anchor text distribution (branded, exact, partial, naked URL)
- Toxic / spammy backlink detection
- Lost backlinks
- Competitor link gap

---

### `/kimi-seo:seo cluster [command] <seed-keyword>`

SERP-based semantic topic clustering for content architecture planning. Built on the Pro Hub Challenge Semantic Cluster Engine. Subcommands: `plan <seed>` (full planning workflow; also `plan --from strategy` to import a `/kimi-seo:seo plan` output), `execute` (create content via a blog-writing skill or output briefs), `map` (regenerate the interactive visualization). Bare `/kimi-seo:seo cluster <seed>` is shorthand for `plan`.

**Examples:**
```
/kimi-seo:seo cluster plan "kimi code skills"
/kimi-seo:seo cluster plan --from strategy
/kimi-seo:seo cluster execute
/kimi-seo:seo cluster map
```

**What it produces:**
- Keyword expansion from the seed (50-200 candidates)
- Pairwise SERP overlap comparison to detect semantic clusters
- Intent classification per cluster (informational, commercial, transactional, navigational)
- Hub-and-spoke content architecture proposal
- Internal link matrix between cluster pages
- Interactive `cluster-map.html` visualization

---

### `/kimi-seo:seo sxo <url>`

Search Experience Optimization: SERP backwards analysis, page-type mismatch detection, persona scoring. Subcommands: `<url> <keyword>` (analyze for a specific keyword), `wireframe <url>` (IST/SOLL wireframe), `personas <url>` (persona-only scoring, skips SERP).

**Examples:**
```
/kimi-seo:seo sxo https://example.com/blog/how-to-x
/kimi-seo:seo sxo https://example.com/page "target keyword"
/kimi-seo:seo sxo wireframe https://example.com/page
/kimi-seo:seo sxo personas https://example.com/page
```

**What it produces:**
- Page-type taxonomy classification (article, landing, product, tool, listing)
- SERP intent vs page-type alignment check
- User stories derived from SERP signals
- Multi-persona scoring (researcher, buyer, expert, casual visitor)
- Wireframe-level recommendations for fixing mismatches

---

### `/kimi-seo:seo drift baseline|compare|history <url>`

SEO drift monitoring. Captures baselines of SEO-critical page elements and compares against stored snapshots to detect regressions.

**Examples:**
```
/kimi-seo:seo drift baseline https://example.com
/kimi-seo:seo drift compare https://example.com
/kimi-seo:seo drift history https://example.com
```

**What it tracks:** title, meta description, canonical, hreflang, Open Graph, schema, headings, internal links, robots, sitemap entry, indexability, Core Web Vitals, response status, redirect chain.

**17 comparison rules** classify changes by severity (CRITICAL, HIGH, MEDIUM). SQLite-backed baselines.

---

### `/kimi-seo:seo ecommerce <url>`

E-commerce SEO covering product schema, marketplace intelligence, and pricing gap analysis. Subcommands: `products <keyword>` (Google Shopping competitive analysis), `gaps <domain>` (organic-vs-Shopping visibility gap), `schema <url>` (product schema validation + enhancement).

**Examples:**
```
/kimi-seo:seo ecommerce https://shop.example.com/product/x
/kimi-seo:seo ecommerce products "running shoes"
/kimi-seo:seo ecommerce gaps shop.example.com
/kimi-seo:seo ecommerce schema https://shop.example.com/product/x
```

**What it analyzes:**
- Product schema (Product, Offer, AggregateRating, Review)
- Google Shopping visibility
- Amazon marketplace presence
- Pricing gap vs competitors
- Out-of-stock and availability signals
- Faceted navigation crawl traps

---

### `/kimi-seo:seo flow [stage] [url|topic]`

FLOW framework integration: evidence-led prompts for the Find, Leverage, Optimize, Win, and Local stages of a content campaign.

**Examples:**
```
/kimi-seo:seo flow find "topic"
/kimi-seo:seo flow leverage https://example.com
/kimi-seo:seo flow optimize https://example.com/page
/kimi-seo:seo flow win https://example.com/page
/kimi-seo:seo flow local https://example.com
/kimi-seo:seo flow prompts
/kimi-seo:seo flow sync
```

**41 prompts** sourced from FLOW (CC BY 4.0). Each prompt is grounded in a specific evidence source (SERP data, GSC, GA4, customer interviews) with attribution preserved.

---

### `/kimi-seo:seo google [command] [url]`

Google SEO APIs. 4-tier credential system covering PageSpeed Insights, CrUX, CrUX History, Search Console, URL Inspection, Indexing API, GA4, and Keyword Planner.

**Setup & reporting:**
```
/kimi-seo:seo google setup                      # Configure/check credentials
/kimi-seo:seo google quotas                     # Show per-API quota usage
/kimi-seo:seo google report full                # Generate full PDF/HTML report
/kimi-seo:seo google report cwv-audit           # CWV-focused report
/kimi-seo:seo google report gsc-performance     # Search performance report
/kimi-seo:seo google report indexation          # Indexation status report
```

**PageSpeed / CrUX (Tier 0):**
```
/kimi-seo:seo google pagespeed <url>            # PageSpeed Insights (lab) + CWV
/kimi-seo:seo google crux <url>                 # CrUX field data
/kimi-seo:seo google crux-history <url>         # 25-week CrUX history
```

**Search Console / Indexing (Tier 1):**
```
/kimi-seo:seo google gsc <property>             # Search Analytics (clicks/impressions/CTR/position)
/kimi-seo:seo google inspect <url>              # URL Inspection (indexation status)
/kimi-seo:seo google inspect-batch <file>       # Batch URL inspection
/kimi-seo:seo google sitemaps <property>        # List submitted sitemaps + status
/kimi-seo:seo google index <url>                # Indexing API notify
/kimi-seo:seo google index-batch <file>         # Batch indexing notify
```
Use Indexing API commands only for pages with JobPosting or BroadcastEvent embedded in VideoObject. Route ordinary URLs to URL Inspection or sitemaps; `URL_UPDATED` does not guarantee indexing.

**GA4 (Tier 2):**
```
/kimi-seo:seo google ga4 [property-id]          # Organic traffic report
/kimi-seo:seo google ga4-pages [property-id]    # Top organic landing pages
```

**NLP / Keywords / YouTube:**
```
/kimi-seo:seo google nlp <url-or-text>          # NLP content analysis
/kimi-seo:seo google entities <url-or-text>     # Entity extraction
/kimi-seo:seo google entity <query>             # Entity lookup
/kimi-seo:seo google keywords <seed>            # Keyword Planner ideas (Tier 3)
/kimi-seo:seo google volume <keywords>          # Keyword search volume (Tier 3)
/kimi-seo:seo google youtube <query>            # YouTube search
/kimi-seo:seo google youtube-video <video_id>   # YouTube video analysis
/kimi-seo:seo google safety <url>               # Safe Browsing check
```

**Tiers:**
- Tier 0 (API key only): PSI, CrUX, CrUX History
- Tier 1 (+ OAuth or Service Account): GSC, URL Inspection, Indexing API
- Tier 2 (+ GA4 property config): GA4 organic traffic
- Tier 3 (+ Google Ads developer token): Keyword Planner

PDF and HTML reports generated via WeasyPrint and matplotlib.

---

### `/kimi-seo:seo image-gen [use-case] <description>`

AI image generation for SEO assets (extension). Powered by Gemini via nanobanana-mcp.

**Prerequisites:** Banana extension installed (`./extensions/banana/install.sh`)

**Use Cases:**
```
/kimi-seo:seo image-gen og <description>          # OG/social preview image (16:9, 1K)
/kimi-seo:seo image-gen hero <description>        # Blog hero image (16:9, 2K)
/kimi-seo:seo image-gen product <description>     # Product photography (4:3, 2K)
/kimi-seo:seo image-gen infographic <description> # Infographic visual (2:3, 4K)
/kimi-seo:seo image-gen custom <description>      # Custom with full Creative Director pipeline
/kimi-seo:seo image-gen batch <description> [N]   # Generate N variations (default: 3)
```

**What it does:**
1. Maps SEO use case to optimized domain mode, aspect ratio, and resolution
2. Constructs 6-component Reasoning Brief (Creative Director pipeline)
3. Generates image via Gemini API
4. Provides SEO checklist (alt text, file naming, WebP, schema markup)

Generated images are saved by the MCP server to `~/Documents/nanobanana_generated/`.

---

### `/kimi-seo:seo firecrawl [command] <url>`

Full-site crawling and URL discovery via Firecrawl MCP (extension).

**Prerequisites:** Firecrawl extension installed (`./extensions/firecrawl/install.sh`)

**Examples:**
```
/kimi-seo:seo firecrawl crawl https://example.com
/kimi-seo:seo firecrawl map https://example.com
/kimi-seo:seo firecrawl scrape https://example.com/page
/kimi-seo:seo firecrawl search "query" https://example.com
```

**What it does:**
- `crawl` walks the site discovering URLs and capturing content
- `map` returns the full URL inventory for a domain
- `scrape` extracts a single page in a model-friendly format
- `search` searches within a crawled site for a query

---

### `/kimi-seo:seo dataforseo [command]`

Live SEO data via DataForSEO MCP server (extension). 23 data commands across 9 API modules, plus cost-tracking commands.

**Prerequisites:** DataForSEO extension installed (`./extensions/dataforseo/install.sh`)

**SERP Analysis:**
```
/kimi-seo:seo dataforseo serp <keyword>              # Google organic results (also Bing/Yahoo)
/kimi-seo:seo dataforseo serp-images <keyword>       # Google Images SERP results
/kimi-seo:seo dataforseo serp-youtube <keyword>      # YouTube search results
/kimi-seo:seo dataforseo youtube <video_id>          # YouTube video deep analysis
```

**Keyword Research:**
```
/kimi-seo:seo dataforseo keywords <seed>             # Keyword ideas and suggestions
/kimi-seo:seo dataforseo volume <keywords>           # Search volume metrics
/kimi-seo:seo dataforseo difficulty <keywords>       # Keyword difficulty scores
/kimi-seo:seo dataforseo intent <keywords>           # Search intent classification
/kimi-seo:seo dataforseo trends <keyword>            # Google Trends data
```

**Domain & Competitors:**
```
/kimi-seo:seo dataforseo backlinks <domain>          # Full backlink profile
/kimi-seo:seo dataforseo competitors <domain>        # Competitor analysis
/kimi-seo:seo dataforseo ranked <domain>             # Ranked keywords
/kimi-seo:seo dataforseo intersection <domains>      # Keyword/backlink overlap
/kimi-seo:seo dataforseo traffic <domains>           # Traffic estimation
/kimi-seo:seo dataforseo subdomains <domain>         # Subdomains with ranking data
/kimi-seo:seo dataforseo top-searches <domain>       # Top queries mentioning domain
```

**Technical / On-Page:**
```
/kimi-seo:seo dataforseo onpage <url>                # On-page analysis (Lighthouse)
/kimi-seo:seo dataforseo tech <domain>               # Technology detection
/kimi-seo:seo dataforseo whois <domain>              # WHOIS data
```

**Content & Business Data:**
```
/kimi-seo:seo dataforseo content <keyword/url>       # Content analysis and trends
/kimi-seo:seo dataforseo listings <keyword>          # Business listings search
```

**AI Visibility / GEO:**
```
/kimi-seo:seo dataforseo ai-scrape <query>           # ChatGPT web scraper for GEO
/kimi-seo:seo dataforseo ai-mentions <keyword>       # LLM mention tracking
```

**Cost Tracking:**
```
/kimi-seo:seo dataforseo costs today                            # Today's DataForSEO spend
/kimi-seo:seo dataforseo costs summary                          # Spend summary across periods
/kimi-seo:seo dataforseo costs config --mode threshold --threshold 0.50   # Set cost-control mode/threshold
```

---

### `/kimi-seo:seo ahrefs [command] <url|topic>`

Ahrefs API metrics (extension). **Prerequisites:** Ahrefs extension installed (`./extensions/ahrefs/install.sh`).
```
/kimi-seo:seo ahrefs metrics <url>       # DR/UR, referring-domain count, organic traffic estimate
/kimi-seo:seo ahrefs backlinks <url>     # Top referring domains, anchor distribution, follow/nofollow ratio
/kimi-seo:seo ahrefs organic <url>       # Organic keywords, ranking distribution, traffic by country
/kimi-seo:seo ahrefs content <topic>     # Content Explorer top results, social shares, referring domains
```

---

### `/kimi-seo:seo bing [command]`

Bing Webmaster Tools + IndexNow (extension). **Prerequisites:** Bing extension installed (`./extensions/bing-webmaster/install.sh`).
```
/kimi-seo:seo bing links <url>                 # Inbound links from Bing Webmaster
/kimi-seo:seo bing compare <urlA> <urlB>       # Compare two URLs' Bing link profiles
/kimi-seo:seo bing submit <url> --host <host>                # IndexNow single-URL submit (requires key)
/kimi-seo:seo bing submit-batch <file> --host <host>         # IndexNow batch submit (requires key)
/kimi-seo:seo bing verify-indexnow --host <host>             # Verify the IndexNow key is published
```

---

### `/kimi-seo:seo profound [command] <brand>`

LLM brand-citation tracking via Profound (extension). **Prerequisites:** Profound extension installed.
```
/kimi-seo:seo profound citations <brand>     # Citation rate per LLM + 30-day trend
/kimi-seo:seo profound prompts <brand>       # Top prompts that surface (or miss) the brand
/kimi-seo:seo profound competitors <brand>   # Brands cited alongside yours for the same prompts
/kimi-seo:seo profound alerts <brand>        # Spike/drop alerts vs 7-day baseline
```

---

### `/kimi-seo:seo seranking [command] <brand|keyword|url>`

AI-visibility + SERP via SE Ranking (extension). **Prerequisites:** SE Ranking extension installed.
```
/kimi-seo:seo seranking ai-visibility <brand>   # Share-of-voice across ChatGPT/Gemini/Perplexity/AI Overviews/AI Mode
/kimi-seo:seo seranking serp <keyword>          # Top 100 organic positions + SERP features
/kimi-seo:seo seranking backlinks <url>         # Backlink profile (free-tier alternative to Ahrefs/DataForSEO)
/kimi-seo:seo seranking competitors <url>       # Top 10 organic competitors + shared-keyword gaps
```

---

### `/kimi-seo:seo unlighthouse <url>`

Multi-page Lighthouse audit via Unlighthouse (extension, MIT, no API quota). **Prerequisites:** Node 18+ and the unlighthouse npm package (`./extensions/unlighthouse/install.sh`).
```
/kimi-seo:seo unlighthouse https://example.com
/kimi-seo:seo unlighthouse https://example.com --device desktop
/kimi-seo:seo unlighthouse https://example.com --max-routes 50 --output-dir ./reports
```

---

## Quick Reference

| Command | Use Case |
|---------|----------|
| `/kimi-seo:seo audit <url>` | Full website audit with parallel subagents |
| `/kimi-seo:seo page <url>` | Single page analysis |
| `/kimi-seo:seo technical <url>` | Technical SEO across 9 categories |
| `/kimi-seo:seo content <url>` | E-E-A-T and content quality |
| `/kimi-seo:seo content-brief <topic>` | Detailed content brief: keywords, outline, internal links |
| `/kimi-seo:seo schema <url>` | Schema markup detection, validation, generation |
| `/kimi-seo:seo sitemap <url>` | Sitemap validation |
| `/kimi-seo:seo sitemap generate` | Create new sitemap with industry templates |
| `/kimi-seo:seo images <url>` | Image optimization |
| `/kimi-seo:seo geo <url>` | AI search optimization (GEO) |
| `/kimi-seo:seo local <url>` | Local SEO (GBP, citations, reviews) |
| `/kimi-seo:seo maps [command]` | Maps intelligence (geo-grid, GBP audit, competitors) |
| `/kimi-seo:seo backlinks <url>` | Backlink profile analysis |
| `/kimi-seo:seo cluster <seed>` | SERP-based semantic clustering |
| `/kimi-seo:seo sxo <url>` | Search Experience Optimization |
| `/kimi-seo:seo drift baseline\|compare\|history <url>` | SEO drift monitoring |
| `/kimi-seo:seo ecommerce <url>` | E-commerce SEO |
| `/kimi-seo:seo hreflang [url]` | Hreflang and international SEO |
| `/kimi-seo:seo plan <type>` | Strategic planning by industry |
| `/kimi-seo:seo programmatic [url\|plan]` | Programmatic SEO analysis |
| `/kimi-seo:seo competitor-pages [url\|generate]` | Competitor comparison pages |
| `/kimi-seo:seo flow [stage] [url\|topic]` | FLOW framework prompts |
| `/kimi-seo:seo google [command] [url]` | Google SEO APIs (GSC, PSI, CrUX, GA4) |
| `/kimi-seo:seo dataforseo [command]` | Live SEO data (extension) |
| `/kimi-seo:seo image-gen [use-case] <desc>` | AI image generation (extension) |
| `/kimi-seo:seo firecrawl [command] <url>` | Full-site crawling (extension) |
| `/kimi-seo:seo ahrefs [command] <url>` | Backlinks, organic keywords, and content data via the official Ahrefs MCP (extension) |
| `/kimi-seo:seo seranking [command]` | AI Share-of-Voice across ChatGPT, Gemini, Perplexity, AI Overviews, AI Mode (extension) |
| `/kimi-seo:seo profound [command]` | LLM citation tracking with time-series data (extension) |
| `/kimi-seo:seo bing [command] <url>` | Bing Webmaster Tools + IndexNow URL submission (extension) |
| `/kimi-seo:seo unlighthouse <url>` | Multi-page Lighthouse runner, runs locally (extension) |
