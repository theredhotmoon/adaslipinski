import axios from 'axios'
import { i18n } from '@/i18n'

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

api.interceptors.request.use((config) => {
  // Carry the active locale on every request so reads return the right language
  // and admin writes update the language currently being viewed (the
  // Translatable cast merges into this locale). Per-call params win.
  config.params = { lang: i18n.global.locale.value, ...(config.params ?? {}) }

  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  },
)
