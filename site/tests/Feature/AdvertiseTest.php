<?php

namespace Tests\Feature;

use App\Models\AdInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvertiseTest extends TestCase
{
    use RefreshDatabase;

    public function test_advertise_page_renders(): void
    {
        $this->get('/advertise')
            ->assertOk()
            ->assertSee('Advertise')
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="message"', false);
    }

    public function test_inquiry_is_validated_and_persisted(): void
    {
        $response = $this->post('/advertise', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'company' => 'Acme Inc',
            'message' => 'I would like to advertise my SEO tool.',
        ]);

        $response->assertRedirect(route('advertise'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('ad_inquiries', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'company' => 'Acme Inc',
            'is_read' => false,
        ]);
    }

    public function test_inquiry_requires_name_email_and_message(): void
    {
        $this->from('/advertise')->post('/advertise', [])
            ->assertRedirect('/advertise')
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('ad_inquiries', 0);
    }

    public function test_inquiry_rejects_invalid_email(): void
    {
        $this->from('/advertise')->post('/advertise', [
            'name' => 'Jane',
            'email' => 'not-an-email',
            'message' => 'Hello',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('ad_inquiries', 0);
    }

    public function test_honeypot_silently_discards_bot_submissions(): void
    {
        $response = $this->post('/advertise', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'spam',
            'website' => 'http://spam.example', // honeypot field
        ]);

        $response->assertRedirect(route('advertise'))
            ->assertSessionHas('success');

        $this->assertSame(0, AdInquiry::count());
    }
}
