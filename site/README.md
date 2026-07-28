# Kimi SEO — Website

The marketing site and documentation renderer for [Kimi SEO](https://github.com/bentocodeing/kimi-seo),
a free, open-source SEO analysis plugin for Kimi Code CLI (community fork of
[claude-seo](https://github.com/AgriciDaniel/claude-seo) by AgriciDaniel).

Built with Laravel 13, Blade, Tailwind CSS 4 and SQLite. The documentation pages
(`/docs/*`) are rendered on the fly from the markdown files in the repository
root — no content is duplicated.

## Requirements

- PHP 8.3+ (developed on PHP 8.5 via Laravel Herd)
- Composer 2
- Node.js 18+ and npm
- SQLite

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # only if it does not exist yet
# Set ADMIN_EMAIL and ADMIN_PASSWORD in .env (see below), then:
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
php artisan serve                # or use Laravel Herd
```

The site is then available at http://127.0.0.1:8000.

## Configuration (.env)

| Key | Purpose |
|---|---|
| `ADMIN_EMAIL` / `ADMIN_PASSWORD` | Credentials of the single admin user, created by the database seeder. If either is empty, no admin user is seeded. |
| `DONATE_URL` | Target of the "Support Kimi SEO" card on the landing page. Defaults to the GitHub repo. |

Never commit real credentials; `.env` is git-ignored.

## Theming

The site ships with light and dark themes. Light is the default; a sun/moon
toggle in the navigation stores the choice in `localStorage` (key `theme`).
A small blocking script in `<head>` applies the saved theme before first paint
to avoid flashes. `prefers-color-scheme` is intentionally not followed.

## Advertising

- One ad slot, labeled "Advertisement", is shown on the landing page and in
  the docs sidebar.
- The slot is an auto-rotating carousel (5s, pause on hover, no rotation
  under `prefers-reduced-motion`) cycling through all active ads in
  `sort_order` order, followed by a permanent "Your ad here?" CTA card
  linking to `/advertise`. With no active ads it is just the CTA card.
- `/advertise` has a contact form (honeypot spam protection) that stores
  inquiries in the `ad_inquiries` table.
- Uploaded ad images are stored in `storage/app/public/ads` and served via the
  `public/storage` symlink created by `php artisan storage:link`.

## Admin

`/admin` uses session authentication (Laravel Fortify, login only — no
registration, password reset, email verification or two-factor). Guests are
redirected to `/login`. To create the single admin user, set `ADMIN_EMAIL`
and `ADMIN_PASSWORD` in `.env`, then run:

```bash
php artisan migrate --seed    # or: php artisan db:seed
```

The seeder is idempotent (`updateOrCreate`) — re-running it updates the
password. The admin area provides:

- Dashboard with counts (active ads, unread inquiries)
- Ads CRUD with image upload (max 2 MB) or external image URL, active toggle,
  and drag-and-drop ordering (persists to `POST /admin/ads/reorder`; new ads
  are appended at the end of the list)
- Inquiry list with read/unread toggle and delete

## Documentation rendering

`config/docs.php` maps URL slugs to markdown files in the parent repository
(`../README.md`, `../docs/*.md`). Pages are rendered with Laravel's bundled
league/commonmark via `Str::markdown()`. Unknown slugs return 404.

After rendering, two rewrite passes run in `DocsController`:

- `src="assets/…"` / `src="screenshots/…"` (repo-relative images) are
  rewritten to `/media/{path}` — a whitelisted route (`MediaController`)
  that serves files only from the repo-root `assets/` and `screenshots/`
  directories (realpath containment check, image MIME types only,
  `Cache-Control: public, max-age=86400`).
- `href="docs/X.md"` / `href="X.md"` links between repo markdown files are
  rewritten to their `/docs/{slug}` equivalents when the file has a docs
  page (other `.md` links are left untouched).

The landing page also serves the animated terminal demos
(`assets/demo-command.svg`, `assets/demo-audit.svg`) through `/media/…`.

## Tests

```bash
php artisan test
```

Feature tests cover the landing page, docs rendering, the advertise form,
the ad slot behaviour and the admin area (auth, ads CRUD, inquiries).

## Deployment (Laravel Forge)

Deploy the **repository root** as the Forge site, with the web directory set
to `site/public`. Docs and media are read from the repo root via
`base_path('..')` (see `config/docs.php` and `MediaController`), which the
repo layout provides — do not deploy `site/` standalone. Deploys are light:
`ms-playwright/` and `.venv/` are git-ignored and `.git` is ~10 MB.

Deploy script:

```bash
cd site
composer install --no-dev
npm ci && npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
```

Notes:

- `php artisan storage:link` is required once (uploaded ad images are served
  from `/storage/…` — relative URLs, so no `APP_URL` dependency — but the
  symlink must exist).
- Set `APP_URL` to the real domain and the admin env vars (`ADMIN_EMAIL`,
  `ADMIN_PASSWORD`), then run `php artisan db:seed` once to create the
  admin user.
- SQLite works fine at this scale; keep `database/database.sqlite` writable
  by the deploy user.
