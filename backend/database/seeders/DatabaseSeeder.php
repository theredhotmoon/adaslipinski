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

    public function run(): void
    {
        // Admin user
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name'     => 'Admin',
            'password' => bcrypt('password123'),
        ]);

        // ── Beneficiary ───────────────────────────────────────────────────────
        Beneficiary::firstOrCreate(['full_name' => 'Adam Lipiński'], [
            'name'             => 'Adaś',
            'age'              => 5,
            'diagnosis'        => 'mózgowe porażenie dziecięce, postać czterokończynowa (tetraplegia spastyczna)',
            'diagnosis_plain'  => 'Adaś nie chodzi i z trudem panuje nad rękami. Każdy ruch wypracowuje godzinami rehabilitacji.',
            'hero_kicker'      => '💛 Razem damy radę',
            'hero_title'       => 'Adaś, 5 lat. Walczymy o każdy ruch.',
            'hero_subtitle'    => 'Adaś ma mózgowe porażenie dziecięce. Nie chodzi, z trudem panuje nad rękami — ale każdego tygodnia robi maleńkie kroki naprzód. Pomożesz mu zrobić kolejny?',
            'cta_label'        => 'Wpłać BLIK-iem',
            'cta_bar_label'    => '💛 Wpłać teraz',
            'recurring_default' => true,
            'nfz_monthly_pln'  => 1200,
        ]);

        // ── Budget items ──────────────────────────────────────────────────────
        $budgetItems = [
            ['fizjo',    'Fizjoterapia NDT-Bobath', 'body',  '8× / mies.',       1600, 'Główna terapia neurorozwojowa — fundament wszystkiego.',              0],
            ['reka',     'Terapia ręki',            'hand',  '4× / mies.',        600, 'Chwyt, manipulacja przedmiotami, samodzielność.',                    1],
            ['hipo',     'Hipoterapia',             'horse', '4× / mies.',        480, 'Napięcie mięśniowe, równowaga i mnóstwo radości.',                   2],
            ['logo',     'Logopedia',               'speak', 'tygodniowo',         480, 'Połykanie i pierwsze słowa.',                                       3],
            ['botulina', 'Botulina',                'drop',  '2× / rok',           600, 'Rozluźnienie spastyczności (koszt rozłożony).',                     4],
            ['sprzet',   'Sprzęt ortopedyczny',    'gear',  'wymiana co rok',     400, 'Ortozy, pionizator, fotelik — dziecko rośnie.',                     5],
            ['turnus',   'Turnusy rehabilitacyjne', 'sun',   '2× / rok',           800, 'Intensywna terapia poza systemem NFZ.',                             6],
        ];

        foreach ($budgetItems as [$slug, $name, $icon, $freq, $cost, $note, $order]) {
            BudgetItem::firstOrCreate(['slug' => $slug], [
                'name' => $name, 'icon' => $icon, 'frequency' => $freq,
                'cost_pln' => $cost, 'note' => $note, 'sort_order' => $order,
            ]);
        }

        // ── Milestones ────────────────────────────────────────────────────────
        $milestones = [
            ['2023', 'Pierwszy raz złapał zabawkę', 0],
            ['2024', 'Siad bez podparcia',          1],
            ['2025', 'Krok w pionizatorze',         2],
            ['2026', 'Stoi sam w pionizatorze',     3],
        ];

        foreach ($milestones as [$year, $label, $order]) {
            Milestone::firstOrCreate(['year' => $year, 'label' => $label], ['sort_order' => $order]);
        }

        // ── Progress posts ────────────────────────────────────────────────────
        $posts = [
            ['Sprzęt',      'Adaś po raz pierwszy stoi sam!',       'Kupiliśmy pionizator dynamiczny za 2 400 zł. Po dwóch dniach Adaś ustał w nim samodzielnie ponad minutę.',                            'Adaś stojący w pionizatorze, uśmiech',   2400, '2026-05-18'],
            ['Terapia',     'Wyprostowane plecy na koniu',           'Na hipoterapii pierwszy raz utrzymał wyprostowaną sylwetkę przez cały przejazd. Fizjoterapeutka nie kryła wzruszenia.',              'Adaś na koniu podczas hipoterapii',       null, '2026-05-04'],
            ['Turnus',      'Wrócił z 14-dniowego turnusu',         'Dwa tygodnie intensywnej terapii. Efekt: dwa samodzielne kroki w pionizatorze i lepsza kontrola głowy.',                              'Adaś z fizjoterapeutą na turnusie',      5600, '2026-04-20'],
            ['Terapia ręki','Złapał kredkę i narysował kółko',      'To, co dla innych dzieci jest oczywiste, dla Adasia to miesiące pracy nad chwytem. Udało się.',                                      'Rysunek Adasia — pierwsze kółko',         null, '2026-04-06'],
        ];

        foreach ($posts as [$tag, $title, $body, $imageAlt, $amount, $date]) {
            ProgressPost::firstOrCreate(['title' => $title], [
                'tag' => $tag, 'body' => $body, 'image_alt' => $imageAlt,
                'amount_pln' => $amount, 'published_at' => $date,
            ]);
        }

        // ── Expenses ──────────────────────────────────────────────────────────
        $expenses = [
            ['2026-05-18', 'Pionizator dynamiczny',          2400, 'Ortopedika Sp. z o.o.',         true],
            ['2026-05-10', 'Fizjoterapia NDT-Bobath (8 sesji)', 1600, 'Centrum Rehabilitacji Ruch', true],
            ['2026-04-28', 'Turnus rehabilitacyjny, 14 dni', 5600, 'Ośrodek Krok po Kroku',        true],
            ['2026-04-12', 'Hipoterapia (4 sesje)',            480, 'Stajnia Pod Dębem',            true],
            ['2026-04-05', 'Logopedia (miesiąc)',              480, 'Poradnia Słowo',               true],
        ];

        foreach ($expenses as [$date, $desc, $amount, $vendor, $invoice]) {
            Expense::firstOrCreate(['expense_date' => $date, 'description' => $desc], [
                'amount_pln' => $amount, 'vendor' => $vendor, 'has_invoice' => $invoice,
            ]);
        }

        // ── Year summary ──────────────────────────────────────────────────────
        YearSummary::updateOrCreate(['year' => 2025], [
            'received_pln' => 58400, 'spent_pln' => 54900, 'balance_pln' => 3500, 'tax_1_5_pln' => 12300,
        ]);

        // ── FAQ ───────────────────────────────────────────────────────────────
        $faq = [
            ['Skąd mam wiedzieć, że to nie scam?',    'Nie zbieramy na konto prywatne. Wszystkie wpłaty trafiają na subkonto Fundacji „Słoneczko" (OPP, KRS 0000186434) z dopiskiem „Adam Lipiński 433/L". Fundacja wypłaca środki wyłącznie na podstawie faktur — każdą publikujemy w zakładce Wydatki.', 0],
            ['Co z 1,5% podatku?',                    'W zeznaniu PIT wpisujesz KRS 0000186434 oraz cel szczegółowy „Adam Lipiński 433/L". To nic nie kosztuje — to część podatku, którą i tak oddajesz państwu, trafia do Adasia.', 1],
            ['Czy dostanę potwierdzenie do PIT?',     'Tak. Darowizny na cele OPP możesz odliczyć od dochodu. Fundacja na prośbę wystawia potwierdzenie wpłaty na subkonto 433/L.', 2],
            ['Czy mogę wpłacać co miesiąc?',          'Tak — to dla nas najważniejsze. BLIK Powtarzalny lub karta cykliczna: zgodę potwierdzasz raz, a wpłaty idą automatycznie. W każdej chwili możesz je zatrzymać.', 3],
        ];

        foreach ($faq as [$q, $a, $order]) {
            FaqItem::firstOrCreate(['question' => $q], ['answer' => $a, 'sort_order' => $order]);
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
        Testimonial::firstOrCreate(['author_name' => 'mgr Anna Wójcik'], [
            'quote_text'  => '„Adaś robi postępy, jakich na początku nikt mu nie wróżył. Regularna terapia ma tu kluczowe znaczenie."',
            'author_role' => 'fizjoterapeutka NDT-Bobath, Centrum Ruch',
        ]);
    }
}
