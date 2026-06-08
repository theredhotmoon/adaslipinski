<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\SetsUpPassport;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use SetsUpPassport;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPassport();
        config(['cms.admin_emails' => ['admin@example.com']]);
    }

    public function test_non_whitelisted_email_is_rejected(): void
    {
        $this->postJson('/api/auth/login', [
            'email'    => 'stranger@example.com',
            'password' => 'whatever123',
        ])->assertStatus(422)->assertJsonValidationErrorFor('email');

        $this->assertDatabaseMissing('users', ['email' => 'stranger@example.com']);
    }

    public function test_first_login_for_whitelisted_email_auto_creates_account_and_returns_token(): void
    {
        $this->postJson('/api/auth/login', [
            'email'    => 'admin@example.com',
            'password' => 'chosen-password',
        ])->assertOk()->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
    }

    public function test_existing_user_with_wrong_password_is_rejected(): void
    {
        User::factory()->create([
            'email'    => 'admin@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $this->postJson('/api/auth/login', [
            'email'    => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422)->assertJsonValidationErrorFor('email');
    }
}
