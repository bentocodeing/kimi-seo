# Search Experience Optimization (SXO) Findings — kimi-seo.com

## Page-type ↔ intent match

| Query intent | Likely landing | Match? |
|---|---|---|
| "kimi seo" (brand/navigational) | `/` | ✅ Clear product identity, install CTA above fold |
| "kimi code seo plugin" (install intent) | `/docs/installation`, `/docs/getting-started` | ✅ Procedural, complete |
| "kimi-seo commands" (reference) | `/docs/commands` | ✅ Full reference, good heading anchors |
| "seo audit cli tool" (non-brand discovery) | nothing targets this | ❌ No page targets generic category queries |
| "migrate kimi-seo v1 to v2" | `/docs/migration-v1-to-v2` | ✅ Exact-match doc exists |
| advertising/sponsor intent | `/advertise` | ✅ Exists, though thin |

No page-type *mismatches* (no blog post trying to rank where a tool page should, etc.) — the corpus is coherent. The gap is **coverage**: nothing addresses non-brand, problem-aware queries ("audit my site from terminal", "seo checks in ci").

## User stories derived from SERP-intent signals

1. *As a Kimi Code user, I want to install the SEO suite in one command so I can audit my site without leaving the terminal.* → served well by `/` + `/docs/installation`.
2. *As a developer evaluating tools, I want proof it works before installing.* → **partially served**: demo GIFs exist on getting-started, but the homepage has no sample output/report excerpt. A linked sample `FULL-AUDIT-REPORT.md` or embedded terminal recording on `/` would close the evaluation loop.
3. *As an existing user, I want to look up a command flag fast.* → served well by `/docs/commands` (deep h3 anchors).
4. *As a v1 user, I want to know what breaks on upgrade.* → served by the migration doc.

## Persona scoring (1–5)

- **Pragmatic evaluator** (wants proof + install friction estimate): 3.5/5 — install path clear, proof thin on homepage.
- **Task-driven user** (returning, wants reference): 4.5/5 — docs hub + commands page are efficient.
- **Non-brand searcher** (doesn't know the product exists): 1.5/5 — no acquisition surface for them.
- **AI engine / answer extractor**: 3/5 — extractable content, missing entity/freshness signals (geo.md).

## Recommendations

1. **(MEDIUM)** Homepage: add a sample-output section (excerpt of a real audit report or linked artifact) — converts evaluators; also adds quotable content for AI engines.
2. **(LOW, strategic)** 2–3 problem-aware pages/posts targeting category queries ("SEO audit from the command line", "SEO checks for CLI workflows") — the only path to non-brand traffic at current authority.
3. **(LOW)** Add per-doc "Was this helpful? → GitHub issue" links — feedback loop + engagement signal, near-zero cost.

## Verdict

SXO is not this site's problem: intent match for its actual audience is good. Folded into Content Quality category; strategic gaps tracked in ACTION-PLAN Phase 3.
