# Schema / Structured Data Findings — kimi-seo.com

## Current state

**Zero JSON-LD blocks on all 11 pages** (`structured_data.block_count: 0` sitewide; no Microdata/RDFa either). Nothing to validate because nothing exists. This is the single weakest category of the audit.

## Why it matters here (first-principle observation)

The site is the marketing + documentation surface for an open-source developer tool. Its realistic rich-result and machine-consumption opportunities are unusually clear-cut:

- **SoftwareApplication** (homepage): name, description, `applicationCategory: DeveloperApplication`, `operatingSystem`, `offers` (price 0 — free/open-source), `codeRepository` → GitHub URL, `author`/`maintainer`. This is the canonical way to tell search engines and AI engines *what the product is*.
- **WebSite** (homepage): site name + URL, `alternateName`. Grounds brand/entity identity.
- **BreadcrumbList** (all 8 docs pages): `Docs > {Section}` — eligible for breadcrumb rich results replacing the raw URL in SERPs.
- **TechArticle** (docs pages): headline, description, `dateModified`, author/publisher (Organization), `proficiencyLevel`. Strengthens E-E-A-T signals and AI citability of the documentation corpus.
- **Organization** (site-wide or about/contact surface): name, logo (`/media/assets/logo.svg` exists and serves 200), `sameAs` → both GitHub repos.

## What NOT to add (per current guidelines)

- **HowTo schema**: deprecated by Google since Sept 2023 — do not mark up the installation/workflow guides with HowTo despite the tempting fit.
- **FAQPage**: Google retired FAQ rich results for all sites on 2026-05-07. Do not add FAQPage for SERP benefit; use QAPage only for genuine user Q&A.

## Recommendation (HIGH)

Ship a small JSON-LD layer in the Blade layout(s):

1. Homepage: `SoftwareApplication` + `WebSite` + `Organization` (one `@graph`).
2. Docs layout: `TechArticle` + `BreadcrumbList`, with `dateModified` sourced from the markdown file mtime or a frontmatter field.
3. Validate with `kimi-seo run parse_html.py` (structured_data blocks) and Google Rich Results Test after deploy.

**Dependency**: none — independent of the sitemap work; can ship in the same release.
**Falsifiability**: if 6 weeks after deployment GSC shows no breadcrumb rich-result impressions and no change in CTR on docs queries, schema was not the constraint for this site's CTR.
**Leading indicator**: Rich Results Test "eligible" status + GSC Enhancements reports.

## Category score: 15/100
No errors (nothing to be wrong), but a complete absence of machine-readable identity for a product site — maximum headroom.
