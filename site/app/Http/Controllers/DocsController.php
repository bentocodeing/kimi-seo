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
            'ads' => Ad::activeOrdered(),
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
        [$html, $toc] = $this->addHeadingAnchors($html);

        return view('docs.show', [
            'slug' => $slug,
            'title' => $page['title'],
            'content' => $html,
            'toc' => $toc,
            'pages' => $pages,
            'ads' => Ad::activeOrdered(),
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

    /**
     * Add GitHub-style id attributes to h1–h4 headings so the markdown's
     * in-page TOC links (#quick-start, #commands, …) work, and collect the
     * h2/h3 entries for the right-rail table of contents.
     *
     * @return array{0: string, 1: array<int, array{level: int, id: string, text: string}>}
     */
    private function addHeadingAnchors(string $html): array
    {
        $seen = [];
        $toc = [];

        $html = preg_replace_callback(
            '#<h([1-4])>(.*?)</h\1>#s',
            function (array $m) use (&$seen, &$toc) {
                $text = html_entity_decode(strip_tags($m[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $base = $this->githubSlug($text);

                $slug = $base;
                $suffix = 0;
                while (isset($seen[$slug])) {
                    $slug = $base.'-'.(++$suffix);
                }
                $seen[$slug] = true;

                if (in_array((int) $m[1], [2, 3], true)) {
                    $toc[] = ['level' => (int) $m[1], 'id' => $slug, 'text' => trim($text)];
                }

                // The brand name inside headings gets the accent gradient
                // (.brand-mark), in the heading's own font.
                $inner = str_replace(
                    'Kimi SEO',
                    '<span class="brand-mark">Kimi SEO</span>',
                    $m[2],
                );

                return '<h'.$m[1].' id="'.$slug.'">'.$inner.'</h'.$m[1].'>';
            },
            $html,
        );

        return [$html, $toc];
    }

    /**
     * GitHub's heading slug algorithm: lowercase, drop every character that
     * is not a letter, number, space, hyphen or underscore, spaces→hyphens.
     */
    private function githubSlug(string $text): string
    {
        $slug = mb_strtolower(trim($text));
        $slug = preg_replace('/[^\p{L}\p{N} _-]+/u', '', $slug);

        return str_replace(' ', '-', $slug);
    }
}
