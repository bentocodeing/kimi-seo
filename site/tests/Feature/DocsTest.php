<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocsTest extends TestCase
{
    use RefreshDatabase;
    public function test_docs_index_renders_and_lists_all_pages(): void
    {
        $response = $this->get('/docs');

        $response->assertOk()->assertSee('Documentation');

        foreach (config('docs.pages') as $page) {
            $response->assertSee($page['title']);
        }
    }

    public function test_every_docs_slug_renders_markdown_as_html(): void
    {
        foreach (config('docs.pages') as $slug => $page) {
            $response = $this->get("/docs/{$slug}");

            $response->assertOk();
            // Rendered markdown produces HTML tags, not raw markdown.
            $response->assertSee('<', false);
            $this->assertMatchesRegularExpression('/<(h1|h2|p|ul|pre)[ >]/', $response->getContent());
        }
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/docs/does-not-exist')->assertNotFound();
    }
}
