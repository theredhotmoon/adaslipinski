<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteContentLocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_returns_english_content_when_lang_en(): void
    {
        $this->getJson('/api/cms/site?lang=en')
            ->assertOk()
            ->assertJsonPath('budget.items.0.name', 'NDT-Bobath physiotherapy')
            ->assertJsonPath('faq.0.q', "How do I know this isn't a scam?")
            ->assertJsonPath('child.heroTitle', 'Adaś, age 5. Fighting for every movement.');
    }

    public function test_returns_polish_content_when_lang_pl(): void
    {
        $this->getJson('/api/cms/site?lang=pl')
            ->assertOk()
            ->assertJsonPath('budget.items.0.name', 'Fizjoterapia NDT-Bobath')
            ->assertJsonPath('faq.0.q', 'Skąd mam wiedzieć, że to nie scam?')
            ->assertJsonPath('child.heroTitle', 'Adaś, 5 lat. Walczymy o każdy ruch.');
    }

    public function test_unsupported_locale_falls_back_to_default(): void
    {
        // ?lang=de is ignored by SetLocale → app default locale (en) is used.
        $this->getJson('/api/cms/site?lang=de')
            ->assertOk()
            ->assertJsonPath('child.heroTitle', 'Adaś, age 5. Fighting for every movement.');
    }

    public function test_payload_has_expected_shape(): void
    {
        $this->getJson('/api/cms/site?lang=pl')
            ->assertOk()
            ->assertJsonStructure([
                'child'  => ['name', 'fullName', 'age', 'heroTitle'],
                'budget' => ['total', 'nfz', 'gap', 'items' => [['id', 'name', 'cost']]],
                'milestones',
                'progress',
                'expenses',
                'faq',
                'foundation' => ['name', 'krs', 'accounts', 'links'],
                'amounts',
            ]);
    }
}
