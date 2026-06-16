import type { SiteContent } from './types'

// Bundled fallback so `astro build` succeeds even when the Laravel API is offline
// (mirrors the SPA's placeholderData in useSiteContent.ts). Content is Polish; when
// the API is reachable, fetchSite() returns properly localized data and this is
// never used. Keep it small but representative.
export const fallback: SiteContent = {
  child: {
    name: 'Adaś',
    fullName: 'Adam Lipiński',
    age: 5,
    diagnosis: 'mózgowe porażenie dziecięce, postać czterokończynowa (tetraplegia spastyczna)',
    diagnosisPlain: 'Adaś nie chodzi i z trudem panuje nad rękami. Każdy ruch wypracowuje godzinami rehabilitacji.',
    heroKicker: '💛 Razem damy radę',
    heroTitle: 'Adaś, 5 lat. Walczymy o każdy ruch.',
    heroSubtitle: 'Adaś ma mózgowe porażenie dziecięce. Nie chodzi, z trudem panuje nad rękami — ale każdego tygodnia robi maleńkie kroki naprzód. Pomożesz mu zrobić kolejny?',
    ctaLabel: 'Wpłać BLIK-iem',
    ctaBarLabel: '💛 Wpłać teraz',
    recurringDefault: true,
  },
  budget: {
    total: 4960,
    nfz: 1200,
    gap: 3760,
    items: [
      { dbId: 1, id: 'fizjo', name: 'Fizjoterapia NDT-Bobath', freq: '8× / mies.', cost: 1600, note: 'Główna terapia neurorozwojowa — fundament wszystkiego.', icon: 'body' },
      { dbId: 2, id: 'reka', name: 'Terapia ręki', freq: '4× / mies.', cost: 600, note: 'Chwyt, manipulacja przedmiotami, samodzielność.', icon: 'hand' },
      { dbId: 3, id: 'hipo', name: 'Hipoterapia', freq: '4× / mies.', cost: 480, note: 'Napięcie mięśniowe, równowaga i mnóstwo radości.', icon: 'horse' },
      { dbId: 4, id: 'logo', name: 'Logopedia', freq: 'tygodniowo', cost: 480, note: 'Połykanie i pierwsze słowa.', icon: 'speak' },
      { dbId: 5, id: 'botulina', name: 'Botulina', freq: '2× / rok', cost: 600, note: 'Rozluźnienie spastyczności (koszt rozłożony).', icon: 'drop' },
      { dbId: 6, id: 'sprzet', name: 'Sprzęt ortopedyczny', freq: 'wymiana co rok', cost: 400, note: 'Ortozy, pionizator, fotelik — dziecko rośnie.', icon: 'gear' },
      { dbId: 7, id: 'turnus', name: 'Turnusy rehabilitacyjne', freq: '2× / rok', cost: 800, note: 'Intensywna terapia poza systemem NFZ.', icon: 'sun' },
    ],
  },
  milestones: [
    { id: 1, year: '2023', text: 'Pierwszy raz złapał zabawkę' },
    { id: 2, year: '2024', text: 'Siad bez podparcia' },
    { id: 3, year: '2025', text: 'Krok w pionizatorze' },
    { id: 4, year: '2026', text: 'Stoi sam w pionizatorze' },
  ],
  progress: [
    { id: 1, date: '18 maja 2026', tag: 'Sprzęt', title: 'Adaś po raz pierwszy stoi sam!', body: 'Kupiliśmy pionizator dynamiczny za 2 400 zł. Po dwóch dniach Adaś ustał w nim samodzielnie ponad minutę.', img: 'Adaś stojący w pionizatorze, uśmiech', amount: 2400 },
    { id: 2, date: '4 maja 2026', tag: 'Terapia', title: 'Wyprostowane plecy na koniu', body: 'Na hipoterapii pierwszy raz utrzymał wyprostowaną sylwetkę przez cały przejazd. Fizjoterapeutka nie kryła wzruszenia.', img: 'Adaś na koniu podczas hipoterapii', amount: null },
    { id: 3, date: '20 kwietnia 2026', tag: 'Turnus', title: 'Wrócił z 14-dniowego turnusu', body: 'Dwa tygodnie intensywnej terapii. Efekt: dwa samodzielne kroki w pionizatorze i lepsza kontrola głowy.', img: 'Adaś z fizjoterapeutą na turnusie', amount: 5600 },
    { id: 4, date: '6 kwietnia 2026', tag: 'Terapia ręki', title: 'Złapał kredkę i narysował kółko', body: 'To, co dla innych dzieci jest oczywiste, dla Adasia to miesiące pracy nad chwytem. Udało się.', img: 'Rysunek Adasia — pierwsze kółko', amount: null },
  ],
  expenses: [
    { id: 1, date: '18.05.2026', desc: 'Pionizator dynamiczny', amount: 2400, place: 'Ortopedika Sp. z o.o.', invoice: true },
    { id: 2, date: '10.05.2026', desc: 'Fizjoterapia NDT-Bobath (8 sesji)', amount: 1600, place: 'Centrum Rehabilitacji Ruch', invoice: true },
    { id: 3, date: '28.04.2026', desc: 'Turnus rehabilitacyjny, 14 dni', amount: 5600, place: 'Ośrodek Krok po Kroku', invoice: true },
    { id: 4, date: '12.04.2026', desc: 'Hipoterapia (4 sesje)', amount: 480, place: 'Stajnia Pod Dębem', invoice: true },
    { id: 5, date: '05.04.2026', desc: 'Logopedia (miesiąc)', amount: 480, place: 'Poradnia Słowo', invoice: true },
  ],
  yearSummary: { year: 2025, in: 58400, out: 54900, left: 3500, tax: 12300 },
  faq: [
    { id: 1, q: 'Skąd mam wiedzieć, że to nie scam?', a: 'Nie zbieramy na konto prywatne. Wszystkie wpłaty trafiają na subkonto Fundacji „Słoneczko" (OPP, KRS 0000186434) z dopiskiem „Adam Lipiński 433/L". Fundacja wypłaca środki wyłącznie na podstawie faktur — każdą publikujemy w zakładce Wydatki.' },
    { id: 2, q: 'Co z 1,5% podatku?', a: 'W zeznaniu PIT wpisujesz KRS 0000186434 oraz cel szczegółowy „Adam Lipiński 433/L". To nic nie kosztuje — to część podatku, którą i tak oddajesz państwu, trafia do Adasia.' },
    { id: 3, q: 'Czy dostanę potwierdzenie do PIT?', a: 'Tak. Darowizny na cele OPP możesz odliczyć od dochodu. Fundacja na prośbę wystawia potwierdzenie wpłaty na subkonto 433/L.' },
    { id: 4, q: 'Czy mogę wpłacać co miesiąc?', a: 'Tak — to dla nas najważniejsze. BLIK Powtarzalny lub karta cykliczna: zgodę potwierdzasz raz, a wpłaty idą automatycznie. W każdej chwili możesz je zatrzymać.' },
  ],
  partners: [
    { id: 1, name: 'I love rolki' },
    { id: 2, name: 'Hedonskate' },
    { id: 3, name: 'Intruz' },
  ],
  foundation: {
    name: 'Fundacja Pomocy Osobom Niepełnosprawnym „Słoneczko”',
    krs: '0000186434',
    nip: '778-14-13-541',
    regon: '634579440',
    cel: 'Adam Lipiński 433/L',
    address: 'ul. Słoneczna 12, 60-001 Poznań',
    web: 'fundacja-sloneczko.pl',
    blikPhone: '555 100 433',
    email: 'kontakt@adaslipinski.pl',
    phone: '+48 555 100 433',
    accounts: [
      { cur: 'PLN', iban: 'PL58 1090 1014 0000 0001 4567 433L' },
      { cur: 'EUR', iban: 'PL12 1090 1014 0000 0002 1133 433L' },
      { cur: 'USD', iban: 'PL77 1090 1014 0000 0003 9921 433L' },
    ],
    links: [
      { label: 'Profil w rejestrze KRS', href: '#' },
      { label: 'Sprawozdania OPP (baza NIW)', href: '#' },
      { label: 'Strona Fundacji „Słoneczko”', href: '#' },
    ],
  },
  amounts: [20, 50, 100, 200],
  testimonials: [],
  gallery: [],
  settings: { layout: 'classic', hiddenSections: [] },
}
