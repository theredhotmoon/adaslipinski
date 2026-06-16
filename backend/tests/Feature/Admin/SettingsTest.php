<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_endpoints_require_authentication(): void
    {
        $this->getJson('/api/admin/settings')->assertUnauthorized();
        $this->putJson('/api/admin/settings', ['layout' => 'editorial'])->assertUnauthorized();
    }

    public function test_show_returns_defaults_and_available_choices(): void
    {
        Passport::actingAs(User::factory()->create());

        $this->getJson('/api/admin/settings')
            ->assertOk()
            ->assertJsonPath('layout', 'classic')
            ->assertJsonPath('hiddenSections', [])
            ->assertJsonPath('availableLayouts', ['classic', 'editorial', 'dashboard'])
            ->assertJsonStructure(['availableSections']);
    }

    public function test_update_persists_layout_and_hidden_sections(): void
    {
        Passport::actingAs(User::factory()->create());

        $this->putJson('/api/admin/settings', [
            'layout' => 'dashboard',
            'hiddenSections' => ['expenses', 'faq'],
        ])
            ->assertOk()
            ->assertJsonPath('layout', 'dashboard')
            ->assertJsonPath('hiddenSections', ['expenses', 'faq']);

        // Reflected in the public payload that both the SPA and Astro site read.
        $this->getJson('/api/cms/site')
            ->assertOk()
            ->assertJsonPath('settings.layout', 'dashboard')
            ->assertJsonPath('settings.hiddenSections', ['expenses', 'faq']);
    }

    public function test_hidden_sections_are_normalized_to_canonical_order(): void
    {
        Passport::actingAs(User::factory()->create());

        // Out of order + duplicate → deduped and returned in canonical section order.
        $this->putJson('/api/admin/settings', ['hiddenSections' => ['faq', 'budget', 'faq']])
            ->assertOk()
            ->assertJsonPath('hiddenSections', ['budget', 'faq']);
    }

    public function test_newly_added_sections_are_toggleable(): void
    {
        Passport::actingAs(User::factory()->create());

        $this->getJson('/api/admin/settings')
            ->assertOk()
            ->assertJsonPath('availableSections', [
                'about', 'budget', 'progress', 'expenses', 'tax',
                'testimonials', 'foundation', 'partners', 'faq', 'gallery',
            ]);

        $this->putJson('/api/admin/settings', ['hiddenSections' => ['about', 'gallery', 'testimonials']])
            ->assertOk()
            ->assertJsonPath('hiddenSections', ['about', 'testimonials', 'gallery']);
    }

    public function test_update_rejects_an_invalid_layout(): void
    {
        Passport::actingAs(User::factory()->create());

        $this->putJson('/api/admin/settings', ['layout' => 'fancy'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('layout');
    }

    public function test_update_rejects_an_unknown_section(): void
    {
        Passport::actingAs(User::factory()->create());

        $this->putJson('/api/admin/settings', ['hiddenSections' => ['budget', 'nope']])
            ->assertStatus(422)
            ->assertJsonValidationErrors('hiddenSections.1');
    }
}
