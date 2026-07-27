---
name: kimi-seo-runtime
description: Orients the Kimi SEO plugin at session start — where the managed runtime lives, how to run bundled scripts from the plugin root, and how to route SEO requests through the seo orchestrator.
type: prompt
disableModelInvocation: true
---

# Kimi SEO runtime orientation

The Kimi SEO suite is installed as a Kimi Code plugin. Keep these facts in mind
for every SEO-related request in this session:

- The managed runtime (Python venv, scripts, schema templates) lives at the
  plugin root. Run bundled scripts as:

  ```bash
  ${KIMI_SKILL_DIR}/../../bin/kimi-seo run <script.py> [args]
  ```

  (this skill lives at `plugin-skills/kimi-seo-runtime/` under the plugin
  root, so `${KIMI_SKILL_DIR}/../..` is the plugin root.) Use
  `${KIMI_SKILL_DIR}/../../bin/kimi-seo setup` to provision the runtime and
  `${KIMI_SKILL_DIR}/../../bin/kimi-seo doctor` for a read-only health check.

- API credentials and user configuration live in `~/.config/kimi-seo/`.

- Route all SEO requests through the `seo` orchestrator skill (`/skill:seo`);
  it dispatches to the 24 sub-skills (audit, page, technical, content, schema,
  sitemap, images, geo, local, maps, plan, hreflang, google, backlinks,
  cluster, sxo, drift, ecommerce, programmatic, competitor-pages, flow,
  content-brief, dataforseo, image-gen).

- Skill bodies that say `./bin/kimi-seo ...` assume the working directory is
  the repository root. When working outside the repo, substitute the
  plugin-root path above (`${KIMI_SKILL_DIR}/../../bin/kimi-seo ...`).
