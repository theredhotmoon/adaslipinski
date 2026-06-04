<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/features/auth/store'

const auth = useAuthStore()

const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)

async function handleSubmit() {
  error.value = ''
  success.value = ''

  if (newPassword.value !== confirmPassword.value) {
    error.value = 'New passwords do not match'
    return
  }

  loading.value = true
  try {
    const result = await auth.changePassword(currentPassword.value, newPassword.value, confirmPassword.value)
    success.value = result.message
    currentPassword.value = ''
    newPassword.value = ''
    confirmPassword.value = ''
  } catch (e: any) {
    const errs = e.response?.data?.errors
    if (errs) {
      error.value = Object.values(errs).flat().join(', ')
    } else {
      error.value = e.response?.data?.message ?? 'Failed to change password'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <main class="page">
    <form class="form" @submit.prevent="handleSubmit">
      <h1>Change password</h1>

      <div v-if="error" class="error">{{ error }}</div>
      <div v-if="success" class="success">{{ success }}</div>

      <label>
        Current password
        <input v-model="currentPassword" type="password" required autocomplete="current-password" />
      </label>

      <label>
        New password
        <input v-model="newPassword" type="password" required minlength="8" autocomplete="new-password" />
      </label>

      <label>
        Confirm new password
        <input v-model="confirmPassword" type="password" required autocomplete="new-password" />
      </label>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Saving…' : 'Change password' }}
      </button>
    </form>
  </main>
</template>

<style scoped>
.page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form {
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

.success {
  color: #16a34a;
  font-size: 0.875rem;
  padding: 0.5rem;
  background: #f0fdf4;
  border-radius: 4px;
}
</style>
