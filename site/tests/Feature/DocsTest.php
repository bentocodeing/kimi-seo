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
        // README.md (overview) embeds assets/cover.svg.
        $this->get('/docs/overview')
            ->assertOk()
            ->assertSee('/media/assets/cover.svg', false)
            ->assertDontSee('src="assets/cover.svg', false);
    }

    public function test_headings_get_github_style_anchor_ids(): void
    {
        $this->get('/docs/getting-started')
            ->assertOk()
            ->assertSee('id="1-install"', false)
            ->assertSee('id="2-run-your-first-audit"', false);

        // Slashes are stripped, leaving double hyphens — GitHub-style.
        $this->get('/docs/overview')
            ->assertOk()
            ->assertSee('id="compared-to-manual--agency--commercial-tools"', false);
    }

    public function test_docs_page_has_right_rail_table_of_contents(): void
    {
        $this->get('/docs/getting-started')
            ->assertOk()
            ->assertSee('On this page')
            ->assertSee('href="#1-install"', false)
            ->assertSee('href="#2-run-your-first-audit"', false);
    }

    public function test_brand_name_in_headings_uses_accent_gradient(): void
    {
        // GETTING-STARTED.md's H1 is "Getting Started with Kimi SEO".
        $this->get('/docs/getting-started')
            ->assertOk()
            ->assertSee('<span class="brand-mark">Kimi SEO</span>', false);
    }

    public function test_repo_internal_markdown_links_are_rewritten_to_docs_pages(): void
    {
        // GETTING-STARTED.md links to INSTALLATION.md and COMMANDS.md.
        $this->get('/docs/getting-started')
            ->assertOk()
            ->assertSee('href="/docs/installation"', false)
            ->assertSee('href="/docs/commands"', false)
            ->assertDontSee('href="docs/INSTALLATION.md"', false);
    }
}
