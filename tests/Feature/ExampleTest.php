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
        $this->get('/soporte')->assertOk()->assertSee('wa.me/528117425048', false)->assertSee('jorge.dzul.escobar@gmail.com');
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

    public function test_access_buttons_open_whatsapp(): void
    {
        $this->get('/')
            ->assertSee('https://wa.me/528117425048?text=Hola%2C%20quiero%20probar%20Lote.', false)
            ->assertDontSee('subject=Quiero', false);
    }

    public function test_hero_shows_the_creator(): void
    {
        $this->get('/')->assertSee('img/creador.jpg', false)->assertSee('@heeydzul');
    }
}
