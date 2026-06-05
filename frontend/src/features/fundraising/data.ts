import { siteConfig } from '@/config/site'

// Identity/branding fields come from the central, rebrandable site config.
// The Polish strings below are fallback *content* (the live copy comes from the
// CMS API and, going forward, the i18n locale files).
export const data = {
  child: {
    name: siteConfig.beneficiary.name,
    fullName: siteConfig.beneficiary.fullName,
    age: siteConfig.beneficiary.age,
    diagnosis: 'mózgowe porażenie dziecięce, postać czterokończynowa (tetraplegia spastyczna)',
    diagnosisShort: 'mózgowe porażenie dziecięce',
    diagnosisPlain: 'Adaś nie chodzi i z trudem panuje nad rękami. Każdy ruch wypracowuje godzinami rehabilitacji.',
  },

  budget: {
    total: 4960,
    nfz: 1200,
    gap: 3760,
    items: [
      { id: 'fizjo', name: 'Fizjoterapia NDT-Bobath', freq: '8× / mies.', cost: 1600, note: 'Główna terapia neurorozwojowa — fundament wszystkiego.', icon: 'body' },
      { id: 'reka', name: 'Terapia ręki', freq: '4× / mies.', cost: 600, note: 'Chwyt, manipulacja przedmiotami, samodzielność.', icon: 'hand' },
      { id: 'hipo', name: 'Hipoterapia', freq: '4× / mies.', cost: 480, note: 'Napięcie mięśniowe, równowaga i mnóstwo radości.', icon: 'horse' },
      { id: 'logo', name: 'Logopedia', freq: 'tygodniowo', cost: 480, note: 'Połykanie i pierwsze słowa.', icon: 'speak' },
      { id: 'botulina', name: 'Botulina', freq: '2× / rok', cost: 600, note: 'Rozluźnienie spastyczności (koszt rozłożony).', icon: 'drop' },
      { id: 'sprzet', name: 'Sprzęt ortopedyczny', freq: 'wymiana co rok', cost: 400, note: 'Ortozy, pionizator, fotelik — dziecko rośnie.', icon: 'gear' },
      { id: 'turnus', name: 'Turnusy rehabilitacyjne', freq: '2× / rok', cost: 800, note: 'Intensywna terapia poza systemem NFZ.', icon: 'sun' },
    ],
  },

  milestones: [
    { year: '2023', text: 'Pierwszy raz złapał zabawkę' },
    { year: '2024', text: 'Siad bez podparcia' },
    { year: '2025', text: 'Krok w pionizatorze' },
    { year: '2026', text: 'Stoi sam w pionizatorze' },
  ],

  progress: [
    { id: 'p1', date: '18 maja 2026', tag: 'Sprzęt', title: 'Adaś po raz pierwszy stoi sam!', body: 'Kupiliśmy pionizator dynamiczny za 2 400 zł. Po dwóch dniach Adaś ustał w nim samodzielnie ponad minutę.', img: 'Adaś stojący w pionizatorze, uśmiech', amount: 2400 },
    { id: 'p2', date: '4 maja 2026', tag: 'Terapia', title: 'Wyprostowane plecy na koniu', body: 'Na hipoterapii pierwszy raz utrzymał wyprostowaną sylwetkę przez cały przejazd. Fizjoterapeutka nie kryła wzruszenia.', img: 'Adaś na koniu podczas hipoterapii', amount: null },
    { id: 'p3', date: '20 kwietnia 2026', tag: 'Turnus', title: 'Wrócił z 14-dniowego turnusu', body: 'Dwa tygodnie intensywnej terapii. Efekt: dwa samodzielne kroki w pionizatorze i lepsza kontrola głowy.', img: 'Adaś z fizjoterapeutą na turnusie', amount: 5600 },
    { id: 'p4', date: '6 kwietnia 2026', tag: 'Terapia ręki', title: 'Złapał kredkę i narysował kółko', body: 'To, co dla innych dzieci jest oczywiste, dla Adasia to miesiące pracy nad chwytem. Udało się.', img: 'Rysunek Adasia — pierwsze kółko', amount: null },
  ],

  expenses: [
    { date: '18.05.2026', desc: 'Pionizator dynamiczny', amount: 2400, place: 'Ortopedika Sp. z o.o.', invoice: true },
    { date: '10.05.2026', desc: 'Fizjoterapia NDT-Bobath (8 sesji)', amount: 1600, place: 'Centrum Rehabilitacji Ruch', invoice: true },
    { date: '28.04.2026', desc: 'Turnus rehabilitacyjny, 14 dni', amount: 5600, place: 'Ośrodek Krok po Kroku', invoice: true },
    { date: '12.04.2026', desc: 'Hipoterapia (4 sesje)', amount: 480, place: 'Stajnia Pod Dębem', invoice: true },
    { date: '05.04.2026', desc: 'Logopedia (miesiąc)', amount: 480, place: 'Poradnia Słowo', invoice: true },
  ],

  yearSummary: { year: '2025', in: 58400, out: 54900, left: 3500, tax: 12300 },

  faq: [
    { q: 'Skąd mam wiedzieć, że to nie scam?', a: 'Nie zbieramy na konto prywatne. Wszystkie wpłaty trafiają na subkonto Fundacji „Słoneczko" (OPP, KRS 0000186434) z dopiskiem „Adam Lipiński 433/L". Fundacja wypłaca środki wyłącznie na podstawie faktur — każdą publikujemy w zakładce Wydatki.' },
    { q: 'Co z 1,5% podatku?', a: 'W zeznaniu PIT wpisujesz KRS 0000186434 oraz cel szczegółowy „Adam Lipiński 433/L". To nic nie kosztuje — to część podatku, którą i tak oddajesz państwu, trafia do Adasia.' },
    { q: 'Czy dostanę potwierdzenie do PIT?', a: 'Tak. Darowizny na cele OPP możesz odliczyć od dochodu. Fundacja na prośbę wystawia potwierdzenie wpłaty na subkonto 433/L.' },
    { q: 'Czy mogę wpłacać co miesiąc?', a: 'Tak — to dla nas najważniejsze. BLIK Powtarzalny lub karta cykliczna: zgodę potwierdzasz raz, a wpłaty idą automatycznie. W każdej chwili możesz je zatrzymać.' },
  ],

  partners: [...siteConfig.partners],

  foundation: {
    name: siteConfig.foundation.name,
    krs: siteConfig.foundation.krs,
    nip: siteConfig.foundation.nip,
    regon: siteConfig.foundation.regon,
    cel: siteConfig.foundation.purpose,
    address: siteConfig.foundation.address,
    web: siteConfig.foundation.web,
    blikPhone: siteConfig.foundation.blikPhone,
    accounts: siteConfig.foundation.accounts.map((a) => ({ ...a })),
    links: siteConfig.foundation.links.map((l) => ({ ...l })),
  },

  contact: {
    email: siteConfig.contact.email,
    phone: siteConfig.contact.phone,
  },

  amounts: [...siteConfig.donation.amounts],
}

export const theme = {
  c: {
    bg: 'oklch(0.985 0.018 95)',
    surface: 'oklch(1 0 0)',
    surfaceAlt: 'oklch(0.965 0.03 95)',
    ink: 'oklch(0.28 0.02 80)',
    inkSoft: 'oklch(0.5 0.02 80)',
    line: 'oklch(0.9 0.02 90)',
    primary: 'oklch(0.62 0.14 150)',
    primaryInk: 'oklch(0.99 0.01 150)',
    primarySoft: 'oklch(0.94 0.05 150)',
    accent: 'oklch(0.84 0.14 90)',
    accentInk: 'oklch(0.32 0.06 80)',
    heroBg: 'oklch(0.95 0.045 95)',
    navActive: 'oklch(0.62 0.14 150)',
  },
  r: 22,
  f: {
    heading: "'Nunito', system-ui, sans-serif",
    body: "'Nunito', system-ui, sans-serif",
    hWeight: 800,
    hLetter: '-0.01em',
  },
  copy: {
    heroKicker: '💛 Razem damy radę',
    heroTitle: 'Adaś, 5 lat. Walczymy o każdy ruch.',
    heroSub: 'Adaś ma mózgowe porażenie dziecięce. Nie chodzi, z trudem panuje nad rękami — ale każdego tygodnia robi maleńkie kroki naprzód. Pomożesz mu zrobić kolejny?',
    cta: 'Wpłać BLIK-iem',
    ctaBar: '💛 Wpłać teraz',
    recurringDefault: true,
  },
}

export type Theme = typeof theme
export type ThemeColors = typeof theme.c

export function zl(n: number): string {
  return n.toLocaleString(siteConfig.currency.intlLocale) + ' ' + siteConfig.currency.suffix
}
