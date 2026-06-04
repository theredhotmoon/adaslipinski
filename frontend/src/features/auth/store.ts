import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from '@/lib/api'

interface User {
  id: number
  name: string
  email: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('auth_token'))

  const isAuthenticated = computed(() => token.value !== null)

  function setToken(newToken: string) {
    token.value = newToken
    localStorage.setItem('auth_token', newToken)
  }

  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('auth_token')
  }

  async function login(email: string, password: string) {
    const { data } = await api.post<{ token: string; user: User }>('/auth/login', {
      email,
      password,
    })
    setToken(data.token)
    user.value = data.user
    return data
  }

  async function fetchMe() {
    const { data } = await api.get<User>('/auth/me')
    user.value = data
    return data
  }

  async function changePassword(currentPassword: string, password: string, passwordConfirmation: string) {
    const { data } = await api.post<{ message: string }>('/auth/change-password', {
      current_password: currentPassword,
      password,
      password_confirmation: passwordConfirmation,
    })
    return data
  }

  function logout() {
    clearAuth()
  }

  return { user, token, isAuthenticated, login, fetchMe, changePassword, logout }
})
