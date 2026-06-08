import { describe, it, expect, beforeEach, vi } from 'vitest'
import { defineComponent, h } from 'vue'
import { mount, flushPromises } from '@vue/test-utils'
import { VueQueryPlugin } from '@tanstack/vue-query'
import { i18n } from '@/i18n'
import { api } from '@/lib/api'
import { useSiteContent } from './useSiteContent'

vi.mock('@/lib/api', () => ({
  api: { get: vi.fn(() => Promise.resolve({ data: { ok: true } })) },
}))

const Host = defineComponent({
  setup() {
    useSiteContent()
    return () => h('div')
  },
})

function mountHost() {
  return mount(Host, { global: { plugins: [VueQueryPlugin, i18n] } })
}

describe('useSiteContent', () => {
  beforeEach(() => {
    vi.mocked(api.get).mockClear()
  })

  it('fetches /cms/site with the active locale', async () => {
    i18n.global.locale.value = 'en'
    mountHost()
    await flushPromises()

    expect(api.get).toHaveBeenCalledWith('/cms/site', { params: { lang: 'en' } })
  })

  it('refetches in the new locale when the language changes', async () => {
    i18n.global.locale.value = 'pl'
    mountHost()
    await flushPromises()

    i18n.global.locale.value = 'en'
    await flushPromises()

    expect(api.get).toHaveBeenCalledWith('/cms/site', { params: { lang: 'en' } })
  })
})
