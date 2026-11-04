<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublicPagesAndAuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_pages_render_with_crisis_notice(): void
    {
        $routes = [
            route('home'),
            route('about'),
            route('contact'),
            route('pricing'),
            route('features'),
            route('faq'),
            route('blog.index'),
            route('demo.request'),
            route('privacy'),
            route('terms'),
            route('cookies'),
            route('data-protection'),
            route('security'),
            route('accessibility'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee('988');
        }
    }

    public function test_multilingual_blog_supports_bengali_and_unicode_slugs(): void
    {
        $response = $this->get('/blog/mental-health-and-modern-counseling-in-bangla');
        $response->assertStatus(200);
        $response->assertSee('মানসিক স্বাস্থ্য');
    }

    public function test_demo_user_can_sign_in_and_access_client_portal(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'client@demo.mhcb.test',
            'password' => 'Demo@12345',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticated();

        // Access dashboard
        $dashResponse = $this->get(route('portal.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Alexander Cole');
    }

    public function test_practitioner_sign_in_redirects_to_admin_panel(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'practitioner@demo.mhcb.test',
            'password' => 'Demo@12345',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }
}
