<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_cookie_is_not_limited_to_localhost(): void
    {
        $this->assertNull(config('session.domain'));
    }

    public function test_guest_sees_login_and_cannot_open_map_or_map_assets(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Masuk ke peta');
        $this->get(route('map'))->assertRedirect(route('login'));
        $this->get(route('map.asset', ['asset' => 'main.js']))
            ->assertRedirect(route('login'));
        $this->get(route('map.asset', ['asset' => 'Jalan_bogor.json']))
            ->assertRedirect(route('login'));
        $this->get(route('map.asset', ['asset' => 'Kecamatan_Clip.json']))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_register_and_open_the_map(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Pengguna WebGIS',
            'email' => 'pengguna@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('map'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'pengguna@example.com']);
        $this->get(route('map'))->assertOk()->assertSee('WebGIS LP2B Kabupaten Bogor');
        $this->get(route('map.asset', ['asset' => 'main.js']))
            ->assertOk();
    }

    public function test_authenticated_user_gets_browser_compatible_map_asset_types(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('map.asset', ['asset' => 'style.css']))
            ->assertOk()
            ->assertHeader('content-type', 'text/css; charset=UTF-8');

        $this->get(route('map.asset', ['asset' => 'main.js']))
            ->assertOk()
            ->assertHeader('content-type', 'text/javascript; charset=UTF-8');
    }

    public function test_authenticated_user_can_load_road_and_clipped_district_data(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('map.asset', ['asset' => 'Jalan_bogor.json']))
            ->assertOk()
            ->assertHeader('content-type', 'application/geo+json');

        $this->get(route('map.asset', ['asset' => 'Kecamatan_Clip.json']))
            ->assertOk()
            ->assertHeader('content-type', 'application/geo+json');
    }

    public function test_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create([
            'email' => 'pengguna@example.com',
            'password' => 'password123',
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('map'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_rejects_an_incorrect_password(): void
    {
        User::factory()->create([
            'email' => 'pengguna@example.com',
            'password' => 'password123',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'pengguna@example.com',
                'password' => 'kata-sandi-salah',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
