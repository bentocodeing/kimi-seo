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
            ->assertSee('How Kimi SEO works')
            ->assertSee('Kimi SEO')
            ->assertSee('orchestrator')
            ->assertSee('how-it-works')
            ->assertSee('data-copy-command', false)
            ->assertSee('Copy install command')
            ->assertSee('data-back-to-top', false)
            ->assertSee('sticky top-0', false)
            ->assertSee('data-sticky-header', false)
            ->assertSee('See it in action')
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
}
