<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_lists_every_public_route(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertSame('application/xml; charset=UTF-8', $response->headers->get('Content-Type'));

        $response
            ->assertSee('<loc>'.route('home').'</loc>', false)
            ->assertSee('<loc>'.route('docs.index').'</loc>', false)
            ->assertSee('<loc>'.route('advertise').'</loc>', false);

        foreach (array_keys(config('docs.pages')) as $slug) {
            $response->assertSee('<loc>'.route('docs.show', $slug).'</loc>', false);
        }
    }

    public function test_sitemap_docs_entries_have_lastmod(): void
    {
        $content = $this->get('/sitemap.xml')->assertOk()->getContent();

        foreach (array_keys(config('docs.pages')) as $slug) {
            $this->assertMatchesRegularExpression(
                '#<loc>'.preg_quote(route('docs.show', $slug), '#').'</loc>\s*<lastmod>\d{4}-\d{2}-\d{2}</lastmod>#',
                $content,
            );
        }
    }

    public function test_sitemap_is_valid_xml(): void
    {
        $content = $this->get('/sitemap.xml')->assertOk()->getContent();

        $xml = simplexml_load_string($content);

        $this->assertNotFalse($xml);
        $this->assertSame(11, $xml->url->count());
    }

    public function test_robots_txt_references_the_sitemap(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Sitemap: https://kimi-seo.com/sitemap.xml', $robots);
    }
}
