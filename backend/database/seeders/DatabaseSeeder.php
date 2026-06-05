<?php

namespace Database\Seeders;

use App\Models\{
    Beneficiary, BudgetItem, DonationAmount, Expense,
    FaqItem, Foundation, FoundationAccount, FoundationLink,
    Milestone, Partner, ProgressPost, Testimonial, User, YearSummary
};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /** Build a translatable value: ['pl' => .., 'en' => ..]. */
    private function t(string $pl, string $en): array
    {
        return ['pl' => $pl, 'en' => $en];
    }

    public function run(): void
    {
        // Admin user
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name'     => 'Admin',
            'password' => bcrypt('password123'),
        ]);

        // ── Beneficiary ───────────────────────────────────────────────────────
        Beneficiary::updateOrCreate(['full_name' => 'Adam Lipiński'], [
            'name'             => 'Adaś',
            'age'              => 5,
            'diagnosis'        => $this->t(
                'mózgowe porażenie dziecięce, postać czterokończynowa (tetraplegia spastyczna)',
                'cerebral palsy, four-limb form (spastic tetraplegia)',
            ),
            'diagnosis_plain'  => $this->t(
                'Adaś nie chodzi i z trudem panuje nad rękami. Każdy ruch wypracowuje godzinami rehabilitacji.',
                "Adaś can't walk and can barely control his hands. Every movement is won through hours of therapy.",
            ),
            'hero_kicker'      => $this->t('💛 Razem damy radę', '💛 Together we can do it'),
            'hero_title'       => $this->t('Adaś, 5 lat. Walczymy o każdy ruch.', 'Adaś, age 5. Fighting for every movement.'),
            'hero_subtitle'    => $this->t(
                'Adaś ma mózgowe porażenie dziecięce. Nie chodzi, z trudem panuje nad rękami — ale każdego tygodnia robi maleńkie kroki naprzód. Pomożesz mu zrobić kolejny?',
                "Adaś has cerebral palsy. He can't walk and can barely control his hands — but every week he takes tiny steps forward. Will you help him take the next one?",
            ),
            'cta_label'        => $this->t('Wpłać BLIK-iem', 'Donate with BLIK'),
            'cta_bar_label'    => $this->t('💛 Wpłać teraz', '💛 Donate now'),
            'recurring_default' => true,
            'nfz_monthly_pln'  => 1200,
        ]);

        // ── Budget items ──────────────────────────────────────────────────────
        BudgetItem::query()->delete();
        $budgetItems = [
            ['fizjo',    $this->t('Fizjoterapia NDT-Bobath', 'NDT-Bobath physiotherapy'), 'body',  $this->t('8× / mies.', '8× / month'),     1600, $this->t('Główna terapia neurorozwojowa — fundament wszystkiego.', 'The core neurodevelopmental therapy — the foundation of everything.'), 0],
            ['reka',     $this->t('Terapia ręki', 'Hand therapy'),                          'hand',  $this->t('4× / mies.', '4× / month'),      600, $this->t('Chwyt, manipulacja przedmiotami, samodzielność.', 'Grip, handling objects, independence.'),                                  1],
            ['hipo',     $this->t('Hipoterapia', 'Hippotherapy'),                           'horse', $this->t('4× / mies.', '4× / month'),      480, $this->t('Napięcie mięśniowe, równowaga i mnóstwo radości.', 'Muscle tone, balance and lots of joy.'),                                 2],
            ['logo',     $this->t('Logopedia', 'Speech therapy'),                           'speak', $this->t('tygodniowo', 'weekly'),          480, $this->t('Połykanie i pierwsze słowa.', 'Swallowing and first words.'),                                                            3],
            ['botulina', $this->t('Botulina', 'Botox'),                                     'drop',  $this->t('2× / rok', '2× / year'),         600, $this->t('Rozluźnienie spastyczności (koszt rozłożony).', 'Relaxing spasticity (cost spread out).'),                                  4],
            ['sprzet',   $this->t('Sprzęt ortopedyczny', 'Orthopedic equipment'),          'gear',  $this->t('wymiana co rok', 'replaced yearly'), 400, $this->t('Ortozy, pionizator, fotelik — dziecko rośnie.', 'Orthoses, stander, seat — the child keeps growing.'),                       5],
            ['turnus',   $this->t('Turnusy rehabilitacyjne', 'Rehabilitation camps'),      'sun',   $this->t('2× / rok', '2× / year'),         800, $this->t('Intensywna terapia poza systemem NFZ.', 'Intensive therapy outside the public health system.'),                          6],
        ];
        foreach ($budgetItems as [$slug, $name, $icon, $freq, $cost, $note, $order]) {
            BudgetItem::create([
                'slug' => $slug, 'name' => $name, 'icon' => $icon, 'frequency' => $freq,
                'cost_pln' => $cost, 'note' => $note, 'sort_order' => $order,
            ]);
        }

        // ── Milestones ────────────────────────────────────────────────────────
        Milestone::query()->delete();
        $milestones = [
            ['2023', $this->t('Pierwszy raz złapał zabawkę', 'Grasped a toy for the first time'), 0],
            ['2024', $this->t('Siad bez podparcia', 'Sat up without support'),                    1],
            ['2025', $this->t('Krok w pionizatorze', 'A step in the stander'),                    2],
            ['2026', $this->t('Stoi sam w pionizatorze', 'Stands on his own in the stander'),     3],
        ];
        foreach ($milestones as [$year, $label, $order]) {
            Milestone::create(['year' => $year, 'label' => $label, 'sort_order' => $order]);
        }

        // ── Progress posts ────────────────────────────────────────────────────
        ProgressPost::query()->delete();
        $posts = [
            [$this->t('Sprzęt', 'Equipment'),       $this->t('Adaś po raz pierwszy stoi sam!', 'Adaś stands on his own for the first time!'), $this->t('Kupiliśmy pionizator dynamiczny za 2 400 zł. Po dwóch dniach Adaś ustał w nim samodzielnie ponad minutę.', 'We bought a dynamic stander for 2,400 zł. After two days Adaś stood in it on his own for over a minute.'), $this->t('Adaś stojący w pionizatorze, uśmiech', 'Adaś standing in the stander, smiling'), 2400, '2026-05-18'],
            [$this->t('Terapia', 'Therapy'),        $this->t('Wyprostowane plecy na koniu', 'A straight back on horseback'),                  $this->t('Na hipoterapii pierwszy raz utrzymał wyprostowaną sylwetkę przez cały przejazd. Fizjoterapeutka nie kryła wzruszenia.', 'During hippotherapy he kept an upright posture for the whole ride for the first time. His physiotherapist was visibly moved.'), $this->t('Adaś na koniu podczas hipoterapii', 'Adaś on a horse during hippotherapy'), null, '2026-05-04'],
            [$this->t('Turnus', 'Camp'),            $this->t('Wrócił z 14-dniowego turnusu', 'Back from a 14-day rehab camp'),               $this->t('Dwa tygodnie intensywnej terapii. Efekt: dwa samodzielne kroki w pionizatorze i lepsza kontrola głowy.', 'Two weeks of intensive therapy. The result: two independent steps in the stander and better head control.'), $this->t('Adaś z fizjoterapeutą na turnusie', 'Adaś with a physiotherapist at the camp'), 5600, '2026-04-20'],
            [$this->t('Terapia ręki', 'Hand therapy'), $this->t('Złapał kredkę i narysował kółko', 'He grabbed a crayon and drew a circle'), $this->t('To, co dla innych dzieci jest oczywiste, dla Adasia to miesiące pracy nad chwytem. Udało się.', "What's obvious for other children meant months of work on grip for Adaś. He did it."), $this->t('Rysunek Adasia — pierwsze kółko', "Adaś's drawing — his first circle"), null, '2026-04-06'],
        ];
        foreach ($posts as [$tag, $title, $body, $imageAlt, $amount, $date]) {
            ProgressPost::create([
                'tag' => $tag, 'title' => $title, 'body' => $body, 'image_alt' => $imageAlt,
                'amount_pln' => $amount, 'published_at' => $date,
            ]);
        }

        // ── Expenses ──────────────────────────────────────────────────────────
        Expense::query()->delete();
        $expenses = [
            ['2026-05-18', $this->t('Pionizator dynamiczny', 'Dynamic stander'),                   2400, 'Ortopedika Sp. z o.o.',      true],
            ['2026-05-10', $this->t('Fizjoterapia NDT-Bobath (8 sesji)', 'NDT-Bobath physiotherapy (8 sessions)'), 1600, 'Centrum Rehabilitacji Ruch', true],
            ['2026-04-28', $this->t('Turnus rehabilitacyjny, 14 dni', 'Rehabilitation camp, 14 days'), 5600, 'Ośrodek Krok po Kroku',      true],
            ['2026-04-12', $this->t('Hipoterapia (4 sesje)', 'Hippotherapy (4 sessions)'),            480, 'Stajnia Pod Dębem',          true],
            ['2026-04-05', $this->t('Logopedia (miesiąc)', 'Speech therapy (one month)'),             480, 'Poradnia Słowo',             true],
        ];
        foreach ($expenses as [$date, $desc, $amount, $vendor, $invoice]) {
            Expense::create([
                'expense_date' => $date, 'description' => $desc, 'amount_pln' => $amount,
                'vendor' => $vendor, 'has_invoice' => $invoice,
            ]);
        }

        // ── Year summary ──────────────────────────────────────────────────────
        YearSummary::updateOrCreate(['year' => 2025], [
            'received_pln' => 58400, 'spent_pln' => 54900, 'balance_pln' => 3500, 'tax_1_5_pln' => 12300,
        ]);

        // ── FAQ ───────────────────────────────────────────────────────────────
        FaqItem::query()->delete();
        $faq = [
            [
                $this->t('Skąd mam wiedzieć, że to nie scam?', "How do I know this isn't a scam?"),
                $this->t(
                    'Nie zbieramy na konto prywatne. Wszystkie wpłaty trafiają na subkonto Fundacji „Słoneczko" (OPP, KRS 0000186434) z dopiskiem „Adam Lipiński 433/L". Fundacja wypłaca środki wyłącznie na podstawie faktur — każdą publikujemy w zakładce Wydatki.',
                    'We don\'t collect to a private account. All donations go to a sub-account of the „Słoneczko" Foundation (a public-benefit organization, KRS 0000186434) with the note „Adam Lipiński 433/L". The foundation releases funds only against invoices — we publish every one in the Expenses tab.',
                ),
                0,
            ],
            [
                $this->t('Co z 1,5% podatku?', 'What about the 1.5% tax?'),
                $this->t(
                    'W zeznaniu PIT wpisujesz KRS 0000186434 oraz cel szczegółowy „Adam Lipiński 433/L". To nic nie kosztuje — to część podatku, którą i tak oddajesz państwu, trafia do Adasia.',
                    'On your PIT tax return enter KRS 0000186434 and the specific purpose „Adam Lipiński 433/L". It costs you nothing — it\'s part of the tax you pay anyway, redirected to Adaś.',
                ),
                1,
            ],
            [
                $this->t('Czy dostanę potwierdzenie do PIT?', 'Will I get a receipt for my tax return?'),
                $this->t(
                    'Tak. Darowizny na cele OPP możesz odliczyć od dochodu. Fundacja na prośbę wystawia potwierdzenie wpłaty na subkonto 433/L.',
                    'Yes. Donations to a public-benefit organization can be deducted from your income. On request the foundation issues a confirmation of payment to sub-account 433/L.',
                ),
                2,
            ],
            [
                $this->t('Czy mogę wpłacać co miesiąc?', 'Can I donate every month?'),
                $this->t(
                    'Tak — to dla nas najważniejsze. BLIK Powtarzalny lub karta cykliczna: zgodę potwierdzasz raz, a wpłaty idą automatycznie. W każdej chwili możesz je zatrzymać.',
                    'Yes — and that matters most to us. Recurring BLIK or a recurring card: you confirm once and donations go through automatically. You can stop them at any time.',
                ),
                3,
            ],
        ];
        foreach ($faq as [$q, $a, $order]) {
            FaqItem::create(['question' => $q, 'answer' => $a, 'sort_order' => $order]);
        }

        // ── Partners ──────────────────────────────────────────────────────────
        foreach (['I love rolki', 'Hedonskate', 'Intruz'] as $i => $name) {
            Partner::firstOrCreate(['name' => $name], ['sort_order' => $i]);
        }

        // ── Foundation ────────────────────────────────────────────────────────
        $foundation = Foundation::firstOrCreate(['krs' => '0000186434'], [
            'name'       => 'Fundacja Pomocy Osobom Niepełnosprawnym „Słoneczko"',
            'nip'        => '778-14-13-541',
            'regon'      => '634579440',
            'cel'        => 'Adam Lipiński 433/L',
            'address'    => 'ul. Słoneczna 12, 60-001 Poznań',
            'web'        => 'fundacja-sloneczko.pl',
            'blik_phone' => '555 100 433',
            'email'      => 'kontakt@adaslipinski.pl',
            'phone'      => '+48 555 100 433',
        ]);

        if ($foundation->accounts()->count() === 0) {
            $accounts = [
                ['PLN', 'PL58 1090 1014 0000 0001 4567 433L', 0],
                ['EUR', 'PL12 1090 1014 0000 0002 1133 433L', 1],
                ['USD', 'PL77 1090 1014 0000 0003 9921 433L', 2],
            ];
            foreach ($accounts as [$cur, $iban, $order]) {
                FoundationAccount::create(['foundation_id' => $foundation->id, 'currency' => $cur, 'iban' => $iban, 'sort_order' => $order]);
            }
        }

        if ($foundation->links()->count() === 0) {
            $links = [
                ['Profil w rejestrze KRS',         '#', 0],
                ['Sprawozdania OPP (baza NIW)',     '#', 1],
                ['Strona Fundacji „Słoneczko"',     '#', 2],
            ];
            foreach ($links as [$label, $url, $order]) {
                FoundationLink::create(['foundation_id' => $foundation->id, 'label' => $label, 'url' => $url, 'sort_order' => $order]);
            }
        }

        // ── Donation amounts ──────────────────────────────────────────────────
        if (DonationAmount::count() === 0) {
            foreach ([20, 50, 100, 200] as $i => $amount) {
                DonationAmount::create(['amount_pln' => $amount, 'sort_order' => $i]);
            }
        }

        // ── Testimonial ───────────────────────────────────────────────────────
        Testimonial::updateOrCreate(['author_name' => 'mgr Anna Wójcik'], [
            'quote_text'  => $this->t(
                '„Adaś robi postępy, jakich na początku nikt mu nie wróżył. Regularna terapia ma tu kluczowe znaczenie."',
                '"Adaś is making progress no one predicted at the start. Regular therapy is the key here."',
            ),
            'author_role' => $this->t('fizjoterapeutka NDT-Bobath, Centrum Ruch', 'NDT-Bobath physiotherapist, Centrum Ruch'),
        ]);
    }
}
