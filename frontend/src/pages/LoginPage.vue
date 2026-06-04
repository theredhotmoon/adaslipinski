<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/features/auth/store'

const router = useRouter()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function handleSubmit() {
  error.value = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/')
  } catch (e: any) {
    error.value = e.response?.data?.errors?.email?.[0] ?? e.response?.data?.message ?? 'Login failed'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <main class="login-page">
    <form class="login-form" @submit.prevent="handleSubmit">
      <h1>Sign in</h1>

      <div v-if="error" class="error">{{ error }}</div>

      <label>
        Email
        <input v-model="email" type="email" required autocomplete="email" />
      </label>

      <label>
        Password
        <input v-model="password" type="password" required autocomplete="current-password" />
      </label>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Signing in…' : 'Sign in' }}
      </button>
    </form>
  </main>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 100%;
  max-width: 360px;
  padding: 2rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
}

h1 {
  margin: 0;
  font-size: 1.5rem;
}

label {
  display: flex;
  flex-direction: column;
  gap: 4px;
  font-size: 0.875rem;
}

input {
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 1rem;
}

button {
  padding: 0.5rem 1rem;
  background: #4f46e5;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error {
  color: #dc2626;
  font-size: 0.875rem;
  padding: 0.5rem;
  background: #fef2f2;
  border-radius: 4px;
}
</style>
