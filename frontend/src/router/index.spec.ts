import { describe, it, expect, beforeEach } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import router from './index'

describe('router auth guard', () => {
  beforeEach(() => {
    localStorage.clear()
    setActivePinia(createPinia())
  })

  it('redirects an anonymous visitor away from the editor to /login', async () => {
    await router.push('/')
    expect(router.currentRoute.value.path).toBe('/login')
  })

  it('lets a signed-in admin open the editor at /', async () => {
    // the auth store seeds its token from localStorage on creation
    localStorage.setItem('auth_token', 'test-token')
    setActivePinia(createPinia())

    await router.push('/')
    expect(router.currentRoute.value.path).toBe('/')
  })

  it('sends a signed-in admin away from /login back to the editor', async () => {
    localStorage.setItem('auth_token', 'test-token')
    setActivePinia(createPinia())

    await router.push('/login')
    expect(router.currentRoute.value.path).toBe('/')
  })
})
