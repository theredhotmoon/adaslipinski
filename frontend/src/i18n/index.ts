import { createI18n } from 'vue-i18n'
import { siteConfig, type LocaleCode } from '@/config/site'
import pl from './locales/pl.json'
import en from './locales/en.json'

const STORAGE_KEY = 'locale'

function initialLocale(): LocaleCode {
  const saved = localStorage.getItem(STORAGE_KEY) as LocaleCode | null
  if (saved && siteConfig.availableLocales.includes(saved)) return saved

  // Fall back to the browser language if we support it, else the site default.
  const browser = navigator.language.slice(0, 2) as LocaleCode
  if (siteConfig.availableLocales.includes(browser)) return browser

  return siteConfig.defaultLocale
}

export const i18n = createI18n({
  legacy: false, // Composition API
  locale: initialLocale(),
  fallbackLocale: siteConfig.defaultLocale,
  messages: { pl, en },
})

/** Switch language, persist the choice, and reflect it on <html lang>. */
export function setLocale(locale: LocaleCode): void {
  i18n.global.locale.value = locale
  localStorage.setItem(STORAGE_KEY, locale)
  document.documentElement.setAttribute('lang', locale)
}

// Apply the initial <html lang> on load.
document.documentElement.setAttribute('lang', i18n.global.locale.value)
