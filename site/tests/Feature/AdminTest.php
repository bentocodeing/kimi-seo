<?php

namespace Tests\Feature;

use App\Models\Ad;
use App\Models\AdInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
    }

    public function test_admin_dashboard_is_reachable_when_authenticated(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Active ads')
            ->assertSee('Unread inquiries');
    }

    public function test_admin_can_create_ad_with_image_url(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/ads', [
                'title' => 'Test Ad',
                'image_url' => 'https://example.com/banner.png',
                'link_url' => 'https://example.com',
                'is_active' => '1',
                'sort_order' => 0,
            ])
            ->assertRedirect(route('admin.ads.index'));

        $this->assertDatabaseHas('ads', [
            'title' => 'Test Ad',
            'image_url' => 'https://example.com/banner.png',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_ad_with_uploaded_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post('/admin/ads', [
                'title' => 'Upload Ad',
                'image' => UploadedFile::fake()->image('banner.png', 600, 200),
                'link_url' => 'https://example.com',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.ads.index'));

        $ad = Ad::where('title', 'Upload Ad')->firstOrFail();
        $this->assertNotNull($ad->image_path);
        Storage::disk('public')->assertExists($ad->image_path);
    }

    public function test_ad_requires_an_image_source(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/ads', [
                'title' => 'No Image',
                'link_url' => 'https://example.com',
            ])
            ->assertSessionHasErrors(['image', 'image_url']);

        $this->assertDatabaseCount('ads', 0);
    }

    public function test_admin_can_toggle_ad(): void
    {
        $ad = Ad::create([
            'title' => 'Toggle Me',
            'image_url' => 'https://example.com/b.png',
            'link_url' => 'https://example.com',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->patch("/admin/ads/{$ad->id}/toggle")
            ->assertRedirect(route('admin.ads.index'));

        $this->assertFalse($ad->fresh()->is_active);
    }

    public function test_admin_can_delete_ad(): void
    {
        $ad = Ad::create([
            'title' => 'Delete Me',
            'image_url' => 'https://example.com/b.png',
            'link_url' => 'https://example.com',
        ]);

        $this->actingAs($this->admin)->delete("/admin/ads/{$ad->id}");

        $this->assertDatabaseMissing('ads', ['id' => $ad->id]);
    }

    public function test_admin_can_manage_inquiries(): void
    {
        $inquiry = AdInquiry::create([
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'message' => 'Interested in the ad slot.',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin/inquiries')
            ->assertOk()
            ->assertSee('jane@example.com');

        $this->actingAs($this->admin)
            ->patch("/admin/inquiries/{$inquiry->id}/toggle-read");
        $this->assertTrue($inquiry->fresh()->is_read);

        $this->actingAs($this->admin)
            ->delete("/admin/inquiries/{$inquiry->id}");
        $this->assertDatabaseMissing('ad_inquiries', ['id' => $inquiry->id]);
    }
}
