/**
 * ─────────────────────────────────────────────────────────────────────────────
 *  CENTRAL SITE CONFIG — edit this file (and the frontend `.env`) to rebrand
 *  the whole site for a different beneficiary. This is the single source of
 *  truth for identity, foundation, bank details, contact, donations, and locales.
 *
 *  Translatable *copy* (story, FAQ, screen text) lives in the CMS / i18n locale
 *  files (`src/i18n/locales/*.json`) — NOT here. Here we keep facts that are the
 *  same in every language: names, numbers, IBANs, links.
 * ─────────────────────────────────────────────────────────────────────────────
 */

export type LocaleCode = 'pl' | 'en'

export interface FoundationAccount {
  cur: string
  iban: string
}

export interface FoundationLink {
  label: string
  href: string
}

export const siteConfig = {
  /** Public site name (used in titles, header, footer). */
  siteName: 'Adaś Lipiński',

  // ── Localization ──────────────────────────────────────────────────────────
  defaultLocale: 'pl' as LocaleCode,
  availableLocales: ['pl', 'en'] as LocaleCode[],

  /** Money formatting. `locale` drives Intl number grouping. */
  currency: {
    code: 'PLN',
    intlLocale: 'pl-PL',
    /** Shown after the amount, e.g. "1 200 zł". */
    suffix: 'zł',
  },

  // ── Beneficiary (the child) ───────────────────────────────────────────────
  beneficiary: {
    name: 'Adaś',
    fullName: 'Adam Lipiński',
    age: 5,
  },

  // ── Foundation handling the donations (OPP / charity) ─────────────────────
  foundation: {
    name: 'Fundacja Pomocy Osobom Niepełnosprawnym „Słoneczko”',
    krs: '0000186434',
    nip: '778-14-13-541',
    regon: '634579440',
    /** Transfer title / earmark required so funds reach this beneficiary. */
    purpose: 'Adam Lipiński 433/L',
    address: 'ul. Słoneczna 12, 60-001 Poznań',
    web: 'fundacja-sloneczko.pl',
    blikPhone: '555 100 433',
    accounts: [
      { cur: 'PLN', iban: 'PL58 1090 1014 0000 0001 4567 433L' },
      { cur: 'EUR', iban: 'PL12 1090 1014 0000 0002 1133 433L' },
      { cur: 'USD', iban: 'PL77 1090 1014 0000 0003 9921 433L' },
    ] as FoundationAccount[],
    links: [
      { label: 'Profil w rejestrze KRS', href: '#' },
      { label: 'Sprawozdania OPP (baza NIW)', href: '#' },
      { label: 'Strona Fundacji „Słoneczko”', href: '#' },
    ] as FoundationLink[],
  },

  // ── Contact ───────────────────────────────────────────────────────────────
  contact: {
    email: 'kontakt@adaslipinski.pl',
    phone: '+48 555 100 433',
  },

  // ── Donations ─────────────────────────────────────────────────────────────
  donation: {
    /** Quick-pick amounts (in the configured currency). */
    amounts: [20, 50, 100, 200],
    /** Whether the donate form defaults to a recurring (monthly) gift. */
    recurringDefault: true,
  },

  /** Supporting brands / sponsors shown in the partners strip. */
  partners: ['I love rolki', 'Hedonskate', 'Intruz'],
} as const

export type SiteConfig = typeof siteConfig
