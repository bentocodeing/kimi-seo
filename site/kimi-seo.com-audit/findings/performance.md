# Performance / Core Web Vitals Findings — kimi-seo.com

**Method note**: PageSpeed Insights and CrUX History API both failed (keyless PSI shared quota exhausted — "240 QPM / 25,000 QPD"; CrUX requires an API key). Fallback: local Lighthouse (mobile profile) against all 11 routes via `unlighthouse_run.py` — **lab data only, no field data**. Domain is likely too new for CrUX field data anyway.

## Lab results (mobile)

| Route | Perf | FCP | LCP | TBT (INP proxy) | CLS | Speed Index |
|---|---|---|---|---|---|---|
| `/` | 0.97 | 0.9s | 0.9s | 190ms | 0 | 2.9s |
| `/docs` | 1.00 | 1.2s | 1.2s | 30ms | 0 | 2.6s |
| `/docs/architecture` | 0.95 | 0.9s | 0.9s | 240ms | 0 | 2.8s |
| `/docs/commands` | 0.95 | 1.0s | 1.0s | 250ms | 0 | 2.6s |
| `/docs/getting-started` | **0.66** | 1.1s | **2.2s** | **2,470ms** | 0 | **4.8s** |
| all other docs routes | 1.00 | ≤0.9s | ≤0.9s | 0ms | 0 | ≤2.3s |
| `/advertise` | 1.00 | 0.9s | 0.9s | 0ms | 0 | 1.8s |

10 of 11 routes pass CWV lab thresholds comfortably (LCP < 2.5s, CLS = 0, TBT < 200ms on most). No render-blocking resources, no unused-JS issues, no console errors, HTTP/2+h3, Brotli — the platform is fast by construction.

## Findings

### 1. `/docs/getting-started` — the most important doc — is the one slow page — HIGH
Page weight 1,830 KiB / 17 requests, driven by two animated GIFs: `/media/screenshots/seo-audit-demo.gif` (989 KB) and `seo-command-demo.gif` (680 KB). Lighthouse attributes the 2,470ms TBT to "Unattributable" main-thread work consistent with animated-GIF decode (script eval is only ~6ms — not a JS problem). LCP 2.2s is near-threshold; on real mid-tier mobile devices + field conditions this page plausibly fails INP/LCP.
**Fix** (pairs with images.md): replace GIFs with `<video autoplay muted loop playsinline>` (mp4/webm, typically 5–10× smaller) or static WebP poster + click-to-play. Also adopt the responsive-image sizes Lighthouse flagged (`uses-responsive-images`, est. 796 KiB savings).
**Falsifiability**: if post-fix lab TBT stays >600ms, the cause was not the GIFs — re-profile.
**Leading indicator**: lab TBT < 200ms and page weight < 500 KiB on this route.

### 2. No edge caching of HTML — LOW
`cf-cache-status: DYNAMIC` on every page (see technical.md §5). Lab TTFB is fine today; risk emerges under load / distant geographies. Monitor once real traffic exists; fix alongside the session-cookie change.

### 3. Accessibility adjacencies — LOW (SEO-relevant via page experience)
Home + `/advertise` score 0.92 a11y: one `color-contrast` failure (ad-slot link) and a `link-name` failure (header logo link has no accessible name — also a minor internal-linking/anchor-signal gap).
**Fix**: add `aria-label="Kimi SEO home"` to the logo link; bump ad-slot link contrast.

## Field-data gap

No CrUX/GSC data available (no API key configured; domain too new). Once `~/.config/kimi-seo/google-api.json` has an `api_key`, re-run `kimi-seo run pagespeed_check.py` and `crux_history.py` for field INP — lab TBT is only a proxy.

## Category score: 88/100
Near-perfect lab performance on 10/11 routes; the flagship doc's GIF weight is a real, user-visible regression and the only thing between this site and a 95+.
