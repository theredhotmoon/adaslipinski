<?php

namespace Tests\Unit\Casts;

use App\Models\Milestone;
use Tests\TestCase;

/**
 * Unit tests for the Translatable cast. Uses an in-memory model (no DB) — the
 * app is still booted by Tests\TestCase so app()->setLocale() works.
 */
class TranslatableTest extends TestCase
{
    private function model(mixed $raw): Milestone
    {
        $m = new Milestone();
        $m->setRawAttributes(['label' => $raw], sync: true);

        return $m;
    }

    public function test_get_returns_value_for_current_locale(): void
    {
        $json = json_encode(['pl' => 'Polski', 'en' => 'English']);

        app()->setLocale('en');
        $this->assertSame('English', $this->model($json)->label);

        app()->setLocale('pl');
        $this->assertSame('Polski', $this->model($json)->label);
    }

    public function test_get_falls_back_to_polish_when_current_locale_missing(): void
    {
        app()->setLocale('en');
        $this->assertSame('Tylko polski', $this->model(json_encode(['pl' => 'Tylko polski']))->label);
    }

    public function test_get_returns_legacy_plain_string_as_is(): void
    {
        app()->setLocale('en');
        $this->assertSame('Legacy text', $this->model('Legacy text')->label);
    }

    public function test_get_returns_null_for_null(): void
    {
        $this->assertNull($this->model(null)->label);
    }

    public function test_set_string_merges_into_current_locale_and_preserves_others(): void
    {
        app()->setLocale('en');
        $m = $this->model(json_encode(['pl' => 'Polski']));
        $m->label = 'English added';

        $stored = json_decode($m->getAttributes()['label'], true);
        $this->assertSame('Polski', $stored['pl']);
        $this->assertSame('English added', $stored['en']);
    }

    public function test_set_array_stores_full_locale_map(): void
    {
        $m = new Milestone();
        $m->label = ['pl' => 'P', 'en' => 'E'];

        $this->assertSame(['pl' => 'P', 'en' => 'E'], json_decode($m->getAttributes()['label'], true));
    }

    public function test_set_null_stores_null(): void
    {
        $m = $this->model(json_encode(['pl' => 'P']));
        $m->label = null;

        $this->assertNull($m->getAttributes()['label']);
    }
}
