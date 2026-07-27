<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Support\Str;

class DocsController extends Controller
{
    public function index()
    {
        return view('docs.index', [
            'pages' => config('docs.pages'),
        ]);
    }

    public function show(string $slug)
    {
        $pages = config('docs.pages');

        abort_unless(isset($pages[$slug]), 404);

        $page = $pages[$slug];

        abort_unless(is_file($page['path']), 404);

        $markdown = file_get_contents($page['path']);

        $html = Str::markdown($markdown);
        $html = $this->rewriteMediaUrls($html);
        $html = $this->rewriteDocLinks($html);

        return view('docs.show', [
            'slug' => $slug,
            'title' => $page['title'],
            'content' => $html,
            'pages' => $pages,
            'ad' => Ad::current(),
        ]);
    }

    /**
     * Repo-relative image sources (assets/…, screenshots/…) become URLs of
     * the whitelisted media route so they resolve on the site.
     */
    private function rewriteMediaUrls(string $html): string
    {
        return preg_replace(
            '#(src=["\'])(?:\./)?((?:assets|screenshots)/)#',
            '$1/media/$2',
            $html,
        );
    }

    /**
     * Links between repo markdown files (docs/COMMANDS.md, ARCHITECTURE.md)
     * become links to the corresponding /docs/{slug} pages, using the same
     * config/docs.php mapping. Files without a docs page are left untouched.
     */
    private function rewriteDocLinks(string $html): string
    {
        $map = [];
        foreach (config('docs.pages') as $pageSlug => $page) {
            $map[basename($page['path'])] = '/docs/'.$pageSlug;
        }

        return preg_replace_callback(
            '#(href=["\'])(?:\./)?(?:docs/)?([A-Za-z0-9-]+\.md)(["\'])#',
            fn (array $m) => isset($map[$m[2]])
                ? $m[1].$map[$m[2]].$m[3]
                : $m[0],
            $html,
        );
    }
}
