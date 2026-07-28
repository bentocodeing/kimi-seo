# Visual / UX Findings — kimi-seo.com

Screenshots captured at 4 viewports for `/` and `/docs` (see `screenshots/`):
- `kimi-seo_com_desktop.png`, `kimi-seo_com_laptop.png`, `kimi-seo_com_tablet.png`, `kimi-seo_com_mobile.png`
- `docs/kimi-seo_com_{desktop,laptop,tablet,mobile}.png`

## Above-the-fold (desktop, home)

- Clear value proposition in the hero ("A full SEO analysis suite for Kimi Code CLI") with terminal-styled install motif — matches the developer audience's expectations.
- Trust-by-numbers row (25 skills / 18 subagents / 53 scripts) visible early — good proof element.
- Primary CTA (install command) is visible without scrolling.

## Mobile

- Layout responsive, no horizontal overflow, no broken elements at 375px width; nav collapses cleanly. Screenshots verified — no visual breakage on any captured viewport.
- Tap targets and font sizes pass Lighthouse mobile checks (no `tap-targets` or `font-size` audit failures).

## Accessibility observations (from Lighthouse)

- Home + `/advertise`: 0.92 — one `color-contrast` failure (ad-slot link) and one `link-name` failure (header logo link lacks an accessible name).
- Docs routes: 0.95–0.96 — minor contrast deductions only.

## Findings

1. **(LOW)** Logo link has no accessible name (`aria-label="Kimi SEO home"`) — a11y + a small internal-link anchor-signal gap.
2. **(LOW)** Ad-slot link contrast below WCAG AA on home/advertise.
3. **(MEDIUM, strategic — from sxo.md)** Homepage shows the product's *description* but not its *output*; a sample report excerpt or terminal recording above the fold would strengthen the evaluation journey. Not a defect — an opportunity.

## Verdict

Visually competent, fast-rendering, mobile-clean site. No Critical or High visual findings. Full-page screenshot review found no layout regressions to fix.
