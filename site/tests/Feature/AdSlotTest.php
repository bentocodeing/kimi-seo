<?php

namespace Tests\Feature;

use App\Models\Ad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdSlotTest extends TestCase
{
    use RefreshDatabase;

    private function makeAd(array $overrides = []): Ad
    {
        return Ad::create(array_merge([
            'title' => 'Awesome SEO Tool',
            'image_url' => 'https://example.com/banner.png',
            'link_url' => 'https://example.com/tool',
            'is_active' => true,
        ], $overrides));
    }

    public function test_cta_card_shown_when_no_ads_exist(): void
    {
        $this->get('/')->assertOk()->assertSee('Your ad here?');
    }

    public function test_cta_card_is_always_present_even_with_active_ads(): void
    {
        $this->makeAd();

        $this->get('/')
            ->assertOk()
            ->assertSee('Awesome SEO Tool')
            ->assertSee('Your ad here?')
            ->assertSee(route('advertise'), false);
    }

    public function test_slot_is_labeled_as_advertisement(): void
    {
        $this->makeAd();

        $this->get('/')
            ->assertOk()
            ->assertSee('Advertisement')
            ->assertDontSee('>Sponsored<', false);
    }

    public function test_inactive_ad_is_not_shown(): void
    {
        $this->makeAd(['title' => 'Hidden Ad', 'is_active' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Hidden Ad')
            ->assertSee('Your ad here?');
    }

    public function test_all_active_ads_render_as_carousel_slides_in_order(): void
    {
        $this->makeAd(['title' => 'Older Low Order', 'sort_order' => 0, 'created_at' => now()->subDay()]);
        $this->makeAd(['title' => 'Higher Order', 'sort_order' => 5]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-ad-carousel', false)
            ->assertSeeInOrder(['Older Low Order', 'Higher Order', 'Your ad here?']);
    }

    public function test_ads_are_shown_in_docs_sidebar(): void
    {
        $this->makeAd(['title' => 'Docs Sidebar Ad']);

        $this->get('/docs/installation')
            ->assertOk()
            ->assertSee('Docs Sidebar Ad')
            ->assertSee('Your ad here?')
            ->assertSee('data-visible="1"', false);
    }

    public function test_cta_card_is_static_below_the_carousel_not_a_slide(): void
    {
        $this->makeAd(['title' => 'Ad One']);
        $this->makeAd(['title' => 'Ad Two']);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('data-ad-carousel', false)
            ->assertSee('data-ad-sr-timer', false)
            // The CTA renders after (below) the carousel…
            ->assertSeeInOrder(['data-ad-carousel', 'Your ad here?'], false);

        // …and is not itself a slide: exactly two .ad-slide elements.
        $this->assertSame(2, substr_count($response->getContent(), 'class="ad-slide '));
    }

    public function test_landing_slot_shows_two_visible_slides(): void
    {
        $this->makeAd();

        $this->get('/')
            ->assertOk()
            ->assertSee('data-visible="2"', false)
            ->assertSee('sm:grid-cols-2', false);
    }

    public function test_uploaded_ad_image_uses_relative_storage_url(): void
    {
        $this->makeAd([
            'image_path' => 'ads/banner.png',
            'image_url' => null,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/ads/banner.png', false)
            ->assertDontSee(config('app.url').'/storage', false);
    }
}
