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

    public function test_repo_relative_images_are_rewritten_to_the_media_route(): void
    {
        // README.md (getting-started) embeds assets/cover.svg.
        $this->get('/docs/getting-started')
            ->assertOk()
            ->assertSee('/media/assets/cover.svg', false)
            ->assertDontSee('src="assets/cover.svg', false);
    }

    public function test_repo_internal_markdown_links_are_rewritten_to_docs_pages(): void
    {
        // README.md links to docs/INSTALLATION.md and docs/COMMANDS.md.
        $this->get('/docs/getting-started')
            ->assertOk()
            ->assertSee('href="/docs/installation"', false)
            ->assertSee('href="/docs/commands"', false)
            ->assertDontSee('href="docs/INSTALLATION.md"', false);
    }
}
