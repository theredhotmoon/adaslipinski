<?php

namespace Tests\Feature\Admin;

use App\Models\BudgetItem;
use App\Models\FaqItem;
use App\Models\ProgressPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(User::factory()->create());
    }

    public function test_editing_with_lang_updates_that_locale_and_preserves_the_other(): void
    {
        $item = BudgetItem::factory()->create([
            'name' => ['pl' => 'PL name', 'en' => 'EN name'],
        ]);

        $this->patchJson("/api/admin/budget-items/{$item->id}?lang=en", ['name' => 'Edited EN'])
            ->assertOk();

        $stored = json_decode($item->fresh()->getRawOriginal('name'), true);
        $this->assertSame('PL name', $stored['pl']);
        $this->assertSame('Edited EN', $stored['en']);
    }

    public function test_can_create_a_faq_item(): void
    {
        $this->postJson('/api/admin/faq', ['question' => 'Is it safe?', 'answer' => 'Yes.'])
            ->assertCreated();

        $this->assertSame(1, FaqItem::count());
    }

    public function test_can_delete_a_faq_item(): void
    {
        $faq = FaqItem::factory()->create();

        $this->deleteJson("/api/admin/faq/{$faq->id}")->assertStatus(204);

        $this->assertSame(0, FaqItem::count());
    }

    public function test_can_delete_a_progress_post(): void
    {
        // Guards the route-model-binding fix for the {progressPost} parameter.
        $post = ProgressPost::factory()->create();

        $this->deleteJson("/api/admin/progress/{$post->id}")->assertStatus(204);

        $this->assertSame(0, ProgressPost::count());
    }
}
