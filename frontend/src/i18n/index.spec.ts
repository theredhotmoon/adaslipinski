import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest'
import { i18n, setLocale, initialLocale } from './index'

describe('i18n', () => {
  beforeEach(() => {
    localStorage.clear()
  })
  afterEach(() => {
    vi.unstubAllGlobals()
    setLocale('pl')
  })

  it('setLocale updates the active locale, localStorage and <html lang>', () => {
    setLocale('en')
    expect(i18n.global.locale.value).toBe('en')
    expect(localStorage.getItem('locale')).toBe('en')
    expect(document.documentElement.getAttribute('lang')).toBe('en')
  })

  it('initialLocale prefers a saved, supported locale', () => {
    localStorage.setItem('locale', 'en')
    expect(initialLocale()).toBe('en')
  })

  it('initialLocale ignores an unsupported saved locale', () => {
    localStorage.setItem('locale', 'fr')
    vi.stubGlobal('navigator', { language: 'fr-FR' })
    expect(initialLocale()).toBe('pl') // site default
  })

  it('initialLocale falls back to a supported browser language', () => {
    vi.stubGlobal('navigator', { language: 'en-GB' })
    expect(initialLocale()).toBe('en')
  })

  it('initialLocale falls back to the site default for an unsupported browser language', () => {
    vi.stubGlobal('navigator', { language: 'de-DE' })
    expect(initialLocale()).toBe('pl')
  })
})
