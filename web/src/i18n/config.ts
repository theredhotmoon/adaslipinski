// Supported locales for the public site. These become URL segments: /pl, /en.
export const LOCALES = ['pl', 'en'] as const
export type Locale = (typeof LOCALES)[number]

export const DEFAULT_LOCALE: Locale = 'pl'

export function isLocale(value: string | undefined): value is Locale {
  return value !== undefined && (LOCALES as readonly string[]).includes(value)
}

// Money formatting matches the SPA's siteConfig.currency (PLN, pl-PL grouping).
export const CURRENCY = { code: 'PLN', intlLocale: 'pl-PL', suffix: 'zł' } as const

/** Format an integer amount as "1 200 zł". */
export function zl(n: number): string {
  return n.toLocaleString(CURRENCY.intlLocale) + ' ' + CURRENCY.suffix
}
