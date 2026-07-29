<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;
    public function test_landing_page_renders(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Kimi SEO')
            ->assertSee('/plugins install https://github.com/bentocodeing/kimi-seo')
            ->assertSee('/kimi-seo:seo audit')
            ->assertSee('/kimi-seo:seo technical')
            ->assertSee('/kimi-seo:seo content')
            ->assertSee('claude-seo')
            ->assertSee('AgriciDaniel')
            ->assertSee('not affiliated with Moonshot AI')
            ->assertSee('Support upstream')
            ->assertSee('Support Kimi SEO')
            ->assertSee('How Kimi SEO <em class="h2-accent">works</em>', false)
            ->assertSee('Kimi SEO')
            ->assertSee('orchestrator')
            ->assertSee('how-it-works')
            ->assertSee('data-copy-command', false)
            ->assertSee('Copy install command')
            ->assertSee('data-back-to-top', false)
            ->assertSee('sticky top-0', false)
            ->assertSee('data-sticky-header', false)
            ->assertSee('See it in <em class="h2-accent">action</em>', false)
            ->assertSee('/media/assets/demo-command.svg')
            ->assertSee('/media/assets/demo-audit.svg');
    }

    public function test_landing_page_shows_ad_placeholder_when_no_active_ad(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Your ad here?')
            ->assertSee(route('advertise'), false);
    }

    public function test_landing_page_has_seo_meta_and_structured_data(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('<meta name="description" content="Audit any website in 10 minutes', false)
            ->assertSee('<link rel="canonical" href="'.url('/').'">', false)
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('"@type":"SoftwareApplication"', false)
            ->assertSee('"codeRepository"', false);
    }

    public function test_public_pages_do_not_share_the_default_meta_description(): void
    {
        $descriptions = [];

        foreach (['/', '/docs', '/advertise'] as $path) {
            $response = $this->get($path)->assertOk();
            preg_match('/<meta name="description" content="([^"]+)"/', $response->getContent(), $m);
            $descriptions[$path] = $m[1] ?? null;
        }

        $this->assertSame(
            count($descriptions),
            count(array_unique($descriptions)),
            'Public pages must each have their own meta description: '.print_r($descriptions, true),
        );
    }
}
