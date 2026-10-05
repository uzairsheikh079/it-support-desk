<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_login_page_renders_for_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Welcome back');
    }

    public function test_valid_credentials_authenticate_and_redirect_to_tickets(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('tickets.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_return_a_validation_error(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'The provided credentials are incorrect.']);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_sign_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
