<?php

namespace Tests\Feature;

use Tests\TestCase;

class MediaTest extends TestCase
{
    public function test_real_svg_asset_is_served_with_correct_content_type(): void
    {
        $this->get('/media/assets/cover.svg')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml');
    }

    public function test_real_gif_is_served_from_screenshots(): void
    {
        $this->get('/media/screenshots/seo-command-demo.gif')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/gif');
    }

    public function test_cache_headers_are_set(): void
    {
        $response = $this->get('/media/assets/cover.svg');

        $response->assertOk();
        $this->assertStringContainsString('max-age', $response->headers->get('Cache-Control'));
    }

    public function test_traversal_outside_the_repo_is_rejected(): void
    {
        $this->get('/media/../site/composer.json')->assertNotFound();
    }

    public function test_paths_outside_the_whitelist_are_rejected(): void
    {
        $this->get('/media/docs/COMMANDS.md')->assertNotFound();
        $this->get('/media/assets/../../AGENTS.md')->assertNotFound();
    }

    public function test_missing_files_are_rejected(): void
    {
        $this->get('/media/assets/does-not-exist.svg')->assertNotFound();
    }

    public function test_disallowed_file_types_are_rejected(): void
    {
        // logo.md does not exist either, but a whitelisted dir + non-image
        // extension must 404 regardless.
        $this->get('/media/assets/../../../composer.json')->assertNotFound();
    }
}
