import { describe, it, expect, afterEach } from 'vitest'
import { mount } from '@vue/test-utils'
import { i18n } from '@/i18n'
import { siteConfig } from '@/config/site'
import FrLangSwitcher from './FrLangSwitcher.vue'

describe('FrLangSwitcher', () => {
  afterEach(() => {
    i18n.global.locale.value = 'pl'
  })

  it('renders one button per available locale', () => {
    const wrapper = mount(FrLangSwitcher, { global: { plugins: [i18n] } })
    expect(wrapper.findAll('button')).toHaveLength(siteConfig.availableLocales.length)
  })

  it('switches the active locale when a locale button is clicked', async () => {
    i18n.global.locale.value = 'pl'
    const wrapper = mount(FrLangSwitcher, { global: { plugins: [i18n] } })

    // availableLocales = ['pl', 'en'] → second button is English
    await wrapper.findAll('button')[1].trigger('click')

    expect(i18n.global.locale.value).toBe('en')
  })
})
