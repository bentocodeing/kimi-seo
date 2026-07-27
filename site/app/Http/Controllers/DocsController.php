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

        return view('docs.show', [
            'slug' => $slug,
            'title' => $page['title'],
            'content' => Str::markdown($markdown),
            'pages' => $pages,
            'ad' => Ad::current(),
        ]);
    }
}
