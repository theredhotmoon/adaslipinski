import pl from './locales/pl.json'
import en from './locales/en.json'
import { DEFAULT_LOCALE, type Locale } from './config'

// UI *chrome* strings (section headings, nav, button labels). Translatable *content*
// (the child's story, FAQ text, etc.) comes from the CMS API already localized — this
// only covers the static scaffolding the CMS doesn't own.
const messages: Record<Locale, Record<string, unknown>> = { pl, en }

type Vars = Record<string, string | number>

function lookup(obj: Record<string, unknown>, path: string): string | undefined {
  const value = path.split('.').reduce<unknown>(
    (acc, key) => (acc && typeof acc === 'object' ? (acc as Record<string, unknown>)[key] : undefined),
    obj,
  )
  return typeof value === 'string' ? value : undefined
}

function interpolate(str: string, vars?: Vars): string {
  if (!vars) return str
  return str.replace(/\{(\w+)\}/g, (_, k) => (k in vars ? String(vars[k]) : `{${k}}`))
}

/**
 * Returns a translator bound to `locale`. Server-side only — all string selection
 * happens during render so islands never need vue-i18n. Falls back to the default
 * locale, then to the key itself, so a missing string is visible, not blank.
 *
 *   const t = useT('en'); t('home.faqTitle')
 */
export function useT(locale: Locale) {
  return (key: string, vars?: Vars): string => {
    const hit = lookup(messages[locale], key) ?? lookup(messages[DEFAULT_LOCALE], key) ?? key
    return interpolate(hit, vars)
  }
}

export type Translator = ReturnType<typeof useT>
