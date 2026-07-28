# Technical SEO Findings — kimi-seo.com

Audited: 2026-07-28 · Scope: full site (11 indexable routes, exhaustive — no sitemap, links crawled from homepage + docs hub)

## What works

- **Crawlability**: `robots.txt` returns 200 `text/plain` with `User-agent: * / Disallow:` (allow all). No meta robots or X-Robots-Tag noindex anywhere; all 11 routes return clean HTTP 200.
- **HTTPS & host canonicalization**: `http://kimi-seo.com/` → 301 → `https://kimi-seo.com/` (single hop); `https://www.kimi-seo.com/` → 301 → apex (single hop). No redirect chains, no mixed content (Lighthouse `is-on-https` passes on all routes).
- **Proper 404s**: `/nonexistent-page-xyz` returns a real 404 status (not a soft-404). `/admin` correctly 302s to `/login`.
- **Transport**: Cloudflare edge, HTTP/2 + HTTP/3 (`alt-svc: h3`), Brotli compression on HTML (`content-encoding: br`), `vary: Accept-Encoding`.
- **Baseline security headers**: `x-frame-options: SAMEORIGIN`, `x-content-type-options: nosniff`, `x-xss-protection: 1; mode=block`; cookies are `secure; httponly; samesite=lax`.
- **Clean rendering**: site is server-rendered Laravel Blade (not an SPA) — zero JS-dependency for content. No console errors on any route.

## Findings

### 1. No XML sitemap — HIGH
`/sitemap.xml` and `/sitemap_index.xml` both return 404, and `robots.txt` declares no `Sitemap:` directive. The domain is **absent from the Common Crawl web graph** (`in_crawl: false` in cc-main-2026-jan-feb-mar), i.e. third-party crawlers have effectively not discovered it yet. For a young domain with near-zero external link signals, a sitemap is the primary discovery channel Google has.
**Fix**: generate `/sitemap.xml` listing the 11 routes (a Laravel package such as `spatie/laravel-sitemap`, or a static file, is sufficient at this scale) and add `Sitemap: https://kimi-seo.com/sitemap.xml` to `robots.txt`. Submit in Google Search Console.
**Falsifiability**: if the sitemap is submitted and 4 weeks later GSC still shows "Discovered – currently not indexed" for most docs pages, the sitemap was not the binding constraint — content/link signals are.
**Leading indicator**: GSC Coverage "Submitted and indexed" count.

### 2. Missing `Strict-Transport-Security` (HSTS) — MEDIUM
No HSTS header on any response. HTTPS is a (light) ranking signal and HSTS prevents SSL-stripping on first visit; it is also a precondition for HSTS preload lists.
**Fix**: add `Strict-Transport-Security: max-age=31536000; includeSubDomains` at the Cloudflare edge (one dashboard toggle) once HTTPS is confirmed stable.

### 3. No `Content-Security-Policy`, `Referrer-Policy`, or `Permissions-Policy` — MEDIUM (security) / LOW (SEO)
Indirect SEO impact (site integrity, referral data quality). `Referrer-Policy: strict-origin-when-cross-origin` preserves referral data in analytics; CSP mitigates injected-content risk that can trigger Safe Browsing flags.
**Fix**: add a report-only CSP first, then enforce; add Referrer-Policy and a minimal Permissions-Policy.

### 4. Fingerprinted static assets cached only 4 hours — LOW
`/build/assets/app-*.css|js` (Vite-fingerprinted, content-hashed filenames) are served `max-age=14400`. Immutable assets should be `max-age=31536000, immutable`. Costs repeat-visit performance, not crawlability.
**Fix**: long-cache headers for `/build/` in the web server or Cloudflare cache rule.

### 5. HTML never edge-cached (`cf-cache-status: DYNAMIC`, `cache-control: no-cache, private`) — LOW
Every pageview hits origin, partly because Laravel sets session + XSRF cookies on anonymous pageviews. Lab TTFB is currently fine (LCP 0.9s on most routes), so this is headroom, not a defect — but it will hurt TTFB under load and for far-from-origin users (CrUX field data).
**Fix**: serve anonymous marketing/docs pages without starting a session, then enable edge cache for GET HTML; or add a Cloudflare cache rule bypassing on cookie presence.

## Category score: 78/100
Excellent crawlability, canonicalization and transport hygiene for a young site; the missing sitemap is the one gap that actually constrains indexation at this domain's current authority level.
