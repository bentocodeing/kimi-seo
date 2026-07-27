<?php

namespace Tests\Feature;

use App\Models\Ad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdSlotTest extends TestCase
{
    use RefreshDatabase;

    public function test_placeholder_shown_when_no_ads_exist(): void
    {
        $this->get('/')->assertOk()->assertSee('Your ad here?');
    }

    public function test_active_ad_is_shown(): void
    {
        Ad::create([
            'title' => 'Awesome SEO Tool',
            'image_url' => 'https://example.com/banner.png',
            'link_url' => 'https://example.com/tool',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Awesome SEO Tool')
            ->assertSee('https://example.com/tool')
            ->assertDontSee('Your ad here?');
    }

    public function test_inactive_ad_is_not_shown(): void
    {
        Ad::create([
            'title' => 'Hidden Ad',
            'image_url' => 'https://example.com/banner.png',
            'link_url' => 'https://example.com/tool',
            'is_active' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Hidden Ad')
            ->assertSee('Your ad here?');
    }

    public function test_ad_is_shown_in_docs_sidebar(): void
    {
        Ad::create([
            'title' => 'Docs Sidebar Ad',
            'image_url' => 'https://example.com/banner.png',
            'link_url' => 'https://example.com/tool',
            'is_active' => true,
        ]);

        $this->get('/docs/installation')
            ->assertOk()
            ->assertSee('Docs Sidebar Ad');
    }

    public function test_lowest_sort_order_wins_then_newest(): void
    {
        Ad::create([
            'title' => 'Older Low Order',
            'image_url' => 'https://example.com/a.png',
            'link_url' => 'https://example.com/a',
            'sort_order' => 0,
            'created_at' => now()->subDay(),
        ]);
        Ad::create([
            'title' => 'Higher Order',
            'image_url' => 'https://example.com/b.png',
            'link_url' => 'https://example.com/b',
            'sort_order' => 5,
        ]);

        $this->get('/')
            ->assertSee('Older Low Order')
            ->assertDontSee('Higher Order');
    }
}
