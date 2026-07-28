# Image SEO Findings — kimi-seo.com

## What works

- **Alt text: 100% coverage** — 15 `<img>` tags sitewide, zero missing alt (verified via parse_html output across all 11 pages).
- No lazy-loading/CLS issues: CLS = 0 on every route (images are dimensioned).
- SVG favicon + ICO fallback present and serving 200; logo served as SVG via `/media/assets/logo.svg`.

## Findings

### 1. Two oversized animated GIFs on the flagship doc — HIGH (shared with performance.md §1)
`/docs/getting-started` embeds `/media/screenshots/seo-audit-demo.gif` (989 KB) and `seo-command-demo.gif` (680 KB) — 1.67 MB of a 1.83 MB page. GIF is the wrong format for screen recordings: poor compression, main-thread decode cost (TBT 2,470ms), no pause/mute control.
**Fix**: convert to `<video autoplay muted loop playsinline>` with mp4 + webm sources (expect ~150–300 KB total), or static WebP posters with click-to-play.

### 2. Oversized raster images / no responsive variants — MEDIUM
Lighthouse `uses-responsive-images`: est. 796 KiB savings on getting-started — images are served at resolutions larger than their display size, with no `srcset`/`sizes`.
**Fix**: generate 2–3 width variants per screenshot and add `srcset`/`sizes`; the docs build step can automate this.

### 3. No modern formats — MEDIUM
`modern-image-formats`: 48 KiB additional savings. Screenshots serve as PNG/GIF; WebP/AVIF would cut transfer further.
**Fix**: convert static screenshots to WebP (keep PNG fallback only if needed; browser support is universal now).

### 4. Missing OG image — (filed under onpage.md §3)
No `og:image` exists to audit; generating one also adds an image-SERP/social asset.

### 5. Image metadata (IPTC/XMP) — INFO
Not embedded. Marginal value for a docs site; skip unless image SERP traffic becomes a goal.

**Falsifiability**: if after conversion the getting-started page still exceeds 600 KiB total or lab TBT stays >600ms, the bottleneck is elsewhere — re-profile with Lighthouse.
**Leading indicator**: getting-started total page weight < 500 KiB; Lighthouse `uses-responsive-images` and `modern-image-formats` audits pass.

## Category score: 65/100
Perfect alt coverage and zero CLS, offset by a 1.67 MB GIF payload on the single most important content page and no responsive/modern-format pipeline.
