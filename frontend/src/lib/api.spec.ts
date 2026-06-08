import { describe, it, expect, beforeEach } from 'vitest'
import type { InternalAxiosRequestConfig } from 'axios'
import { api } from './api'
import { setLocale } from '@/i18n'

// Reach into axios' interceptor handlers to invoke them directly (no network).
function requestInterceptor() {
  return (api.interceptors.request as unknown as {
    handlers: { fulfilled: (c: InternalAxiosRequestConfig) => InternalAxiosRequestConfig }[]
  }).handlers[0].fulfilled
}
function responseRejected() {
  return (api.interceptors.response as unknown as {
    handlers: { rejected: (e: unknown) => unknown }[]
  }).handlers[0].rejected
}

describe('api interceptors', () => {
  beforeEach(() => {
    localStorage.clear()
  })

  it('attaches the active locale as a lang param', () => {
    setLocale('en')
    const cfg = requestInterceptor()({ headers: {} } as InternalAxiosRequestConfig)
    expect(cfg.params.lang).toBe('en')
    setLocale('pl')
    const cfg2 = requestInterceptor()({ headers: {} } as InternalAxiosRequestConfig)
    expect(cfg2.params.lang).toBe('pl')
  })

  it('does not overwrite an explicit per-call lang param', () => {
    setLocale('en')
    const cfg = requestInterceptor()({ headers: {}, params: { lang: 'pl' } } as unknown as InternalAxiosRequestConfig)
    expect(cfg.params.lang).toBe('pl')
  })

  it('adds a Bearer token from localStorage when present', () => {
    localStorage.setItem('auth_token', 'tok-123')
    const cfg = requestInterceptor()({ headers: {} } as InternalAxiosRequestConfig)
    expect(cfg.headers.Authorization).toBe('Bearer tok-123')
  })

  it('clears the token when a response is 401', async () => {
    localStorage.setItem('auth_token', 'tok-123')
    await expect(responseRejected()({ response: { status: 401 } })).rejects.toBeDefined()
    expect(localStorage.getItem('auth_token')).toBeNull()
  })
})
