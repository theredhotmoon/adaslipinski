<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/features/auth/store'

const router = useRouter()
const auth = useAuthStore()

function logout() {
  auth.logout()
  router.push('/login')
}
</script>

<template>
  <main class="page">
    <div class="card">
      <h1>Welcome, {{ auth.user?.name ?? 'User' }}</h1>
      <p>{{ auth.user?.email }}</p>
      <nav>
        <router-link to="/change-password">Change password</router-link>
        <button @click="logout">Sign out</button>
      </nav>
    </div>
  </main>
</template>

<style scoped>
.page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card {
  padding: 2rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

h1 {
  margin: 0;
}

p {
  margin: 0;
  color: #6b7280;
}

nav {
  display: flex;
  gap: 1rem;
  align-items: center;
  margin-top: 0.5rem;
}

button {
  padding: 0.4rem 0.75rem;
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.875rem;
}
</style>
