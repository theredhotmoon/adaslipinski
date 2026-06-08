<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProtectedRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/auth/me')->assertUnauthorized();
    }

    public function test_me_returns_the_authenticated_user(): void
    {
        $user = User::factory()->create(['name' => 'Kuba', 'email' => 'kuba@example.com']);
        Passport::actingAs($user);

        $this->getJson('/api/auth/me')
            ->assertOk()
            ->assertJson(['id' => $user->id, 'name' => 'Kuba', 'email' => 'kuba@example.com']);
    }

    public function test_admin_routes_require_authentication(): void
    {
        $this->postJson('/api/admin/faq', ['question' => 'Q?', 'answer' => 'A'])
            ->assertUnauthorized();
    }
}
