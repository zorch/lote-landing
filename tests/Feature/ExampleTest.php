<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_pages_load(): void
    {
        $this->get('/')->assertOk()->assertSee('Lote hace lo demás.');
        $this->get('/privacidad')->assertOk()->assertSee('drive.file')->assertSee('uso limitado');
        $this->get('/terminos')->assertOk()->assertSee('Términos de uso');
    }

    public function test_trailing_slash_links_still_work(): void
    {
        $this->get('/privacidad/')->assertOk();
    }

    public function test_sitemap_lists_every_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('privacy'), false);
    }

    public function test_contact_email_comes_from_config(): void
    {
        config(['landing.email' => 'hola@applote.com']);
        $this->get('/')->assertSee('mailto:hola@applote.com', false);
    }
}
