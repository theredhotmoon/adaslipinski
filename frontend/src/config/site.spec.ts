import { describe, it, expect } from 'vitest'
import { siteConfig } from './site'

describe('siteConfig', () => {
  it('default locale is one of the available locales', () => {
    expect(siteConfig.availableLocales).toContain(siteConfig.defaultLocale)
  })

  it('exposes donation amounts and a currency suffix', () => {
    expect(siteConfig.donation.amounts.length).toBeGreaterThan(0)
    expect(siteConfig.currency.suffix).toBeTruthy()
    expect(siteConfig.currency.intlLocale).toBeTruthy()
  })
})
