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
            ->assertSee('/seo audit')
            ->assertSee('/seo technical')
            ->assertSee('/seo content')
            ->assertSee('claude-seo')
            ->assertSee('AgriciDaniel')
            ->assertSee('not affiliated with Moonshot AI')
            ->assertSee('Support upstream')
            ->assertSee('Support Kimi SEO');
    }

    public function test_landing_page_shows_ad_placeholder_when_no_active_ad(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Your ad here?')
            ->assertSee(route('advertise'), false);
    }
}
