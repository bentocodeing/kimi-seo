# Content Quality & E-E-A-T Findings — kimi-seo.com

**Business type detected**: open-source developer tool (product marketing site + documentation publisher hybrid). Signals: `/docs` tree, GitHub repository links, plugin install CTA, feature/skill listings. Not local, not e-commerce.

## Content inventory (word counts of main content)

| Page | Words | Assessment |
|---|---|---|
| `/docs/getting-started` | 5,137 | Substantial flagship guide |
| `/docs/commands` | 3,428 | Solid reference |
| `/docs/architecture` | 1,404 | Adequate |
| `/docs/migration-v1-to-v2` | 1,261 | Adequate for purpose |
| `/docs/installation` | 754 | Adequate (procedural) |
| `/docs/troubleshooting` | 701 | Adequate |
| `/docs/workflow` | 648 | Adequate (audience: maintainers) |
| `/docs/mcp-integration` | 590 | Adequate |
| `/` | 433 | Normal for a product landing page |
| `/docs` (hub) | 133 | **Thin** — see onpage.md §4 |
| `/advertise` | 114 | Thin utility page (acceptable) |

No duplicate body content between pages (only the meta description is duplicated — filed under on-page). No keyword stuffing; copy is written for humans. Readability is appropriate for the technical audience.

## E-E-A-T assessment (Sept 2025 QRG lens)

- **Experience/Expertise**: strong implicit signals — the docs demonstrate working knowledge (real commands, real script names, versioned migration guide). The product *is* the expertise demonstration.
- **Authoritativeness**: weak externally. Domain absent from Common Crawl web graph; no evidence of third-party citations. Two GitHub repos are linked but there is no press/community/mention surface on-site (no testimonials, no "used by", no star counts).
- **Trust**: mixed. Open-source + free is a trust positive; HTTPS everywhere. **Gaps**: no About page, no named author/maintainer page, no contact page beyond `/advertise`, no privacy policy / terms / license page linked from the footer, no publication or update dates on docs pages.

## Findings

### 1. No trust/footer pages (privacy, license, about, contact) — MEDIUM
For a YMYL-adjacent tool (it advises on business-critical SEO decisions), the absence of basic trust pages suppresses E-E-A-T evaluation and looks odd next to an `/advertise` page that asks for money.
**Fix**: add footer links: About/Maintainer, License (MIT, per repo), Privacy, Contact. Low effort, high trust ROI.

### 2. No authorship or freshness signals on docs — MEDIUM
Docs pages carry no author, no `datePublished`/`dateModified`. Both human readers and AI engines use freshness to decide whether migration/troubleshooting advice is current. Pairs with the `TechArticle` schema recommendation (schema.md).
**Fix**: surface "Last updated" per doc (derive from repo mtime) and attribute the maintainer.

### 3. Docs hub thin content — MEDIUM
(see onpage.md §4 — 133 words, navigation-only.)

### 4. No blog/changelog surface for content growth — LOW (strategic)
The 11-page corpus covers the product well but targets almost no non-brand queries ("SEO audit CLI", "Kimi Code SEO plugin", "Core Web Vitals CLI tool"). A changelog or 3–5 use-case articles would open non-brand acquisition. Strategic, not corrective — listed for the action plan's Phase 3.

## AI citation readiness (content slice)

Docs are semantic HTML with clean heading structure and self-contained sections — good raw material for AI Overviews/LLM citation. Blockers are structural, not editorial: no schema, no dates, no authorship, no llms.txt (see geo.md).

## Category score: 72/100
Genuinely good documentation corpus for an 11-page site; deductions for missing trust pages, missing freshness/authorship signals, and the thin hub.
