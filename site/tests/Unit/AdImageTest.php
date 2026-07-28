<?php

namespace Tests\Unit;

use App\Models\Ad;
use Tests\TestCase;

class AdImageTest extends TestCase
{
    public function test_uploaded_image_returns_relative_storage_url(): void
    {
        $ad = new Ad(['image_path' => 'ads/banner.png']);

        $this->assertSame('/storage/ads/banner.png', $ad->image());
    }

    public function test_uploaded_image_does_not_depend_on_app_url(): void
    {
        config()->set('app.url', 'http://some-site.test');

        $ad = new Ad(['image_path' => 'ads/banner.png']);

        $this->assertSame('/storage/ads/banner.png', $ad->image());
    }

    public function test_external_image_url_is_passed_through(): void
    {
        $ad = new Ad(['image_url' => 'https://cdn.example.com/banner.png']);

        $this->assertSame('https://cdn.example.com/banner.png', $ad->image());
    }

    public function test_uploaded_image_wins_over_external_url(): void
    {
        $ad = new Ad([
            'image_path' => 'ads/banner.png',
            'image_url' => 'https://cdn.example.com/banner.png',
        ]);

        $this->assertSame('/storage/ads/banner.png', $ad->image());
    }
}
