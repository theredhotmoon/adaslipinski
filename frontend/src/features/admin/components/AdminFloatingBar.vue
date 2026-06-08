<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { Lock, LogOut, Pencil, Eye, EyeOff } from 'lucide-vue-next'
import { useAuthStore } from '@/features/auth/store'

const auth = useAuthStore()
const { t } = useI18n()

// Login form state
const showLogin = ref(false)
const email = ref('')
const password = ref('')
const showPw = ref(false)
const error = ref('')
const loading = ref(false)

async function login() {
  error.value = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    showLogin.value = false
    email.value = ''
    password.value = ''
  } catch (e: any) {
    error.value = e.response?.data?.errors?.email?.[0]
      ?? e.response?.data?.message
      ?? t('admin.loginError')
  } finally {
    loading.value = false
  }
}

function logout() {
  auth.logout()
  showLogin.value = false
}
</script>

<template>
  <!-- Admin status bar (logged in) -->
  <div
    v-if="auth.isAuthenticated"
    class="fixed bottom-[130px] right-3 z-[2000] flex flex-col items-end gap-1"
  >
    <div
      class="flex items-center gap-2 px-3 py-2 rounded-full shadow-lg text-xs font-bold"
      style="background: #065f46; color: #d1fae5; font-family: inherit;"
    >
      <Pencil :size="13" />
      {{ t('admin.mode') }}
      <button
        @click="logout"
        class="ml-1 opacity-70 hover:opacity-100 transition-opacity"
        :title="t('admin.logout')"
      >
        <LogOut :size="13" />
      </button>
    </div>
    <div class="text-[10px] text-white/60 pr-1">{{ auth.user?.email }}</div>
  </div>

  <!-- Login trigger (not logged in) -->
  <div v-else class="fixed bottom-[130px] right-3 z-[2000]">
    <button
      @click="showLogin = !showLogin"
      class="w-9 h-9 rounded-full shadow-lg flex items-center justify-center transition-colors"
      style="background: rgba(0,0,0,0.35); color: rgba(255,255,255,0.7);"
      title="Admin login"
    >
      <Lock :size="15" />
    </button>
  </div>

  <!-- Login modal -->
  <Teleport to="body">
    <div
      v-if="showLogin && !auth.isAuthenticated"
      class="fixed inset-0 z-[3000] flex items-end justify-center"
    >
      <div class="absolute inset-0 bg-black/30" @click="showLogin = false" />
      <div
        class="relative w-full max-w-[430px] bg-white rounded-t-3xl p-6 shadow-2xl animate-sheet-up"
      >
        <h3 class="text-lg font-black mb-4" style="font-family: inherit; color: #065f46;">
          🔐 {{ t('admin.loginTitle') }}
        </h3>

        <div v-if="error" class="mb-3 px-3 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-lg">
          {{ error }}
        </div>

        <label class="block mb-3">
          <span class="text-xs font-bold text-gray-500 mb-1 block">E-mail</span>
          <input
            v-model="email"
            type="email"
            placeholder="admin@example.com"
            autocomplete="email"
            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:border-emerald-400"
            @keydown.enter="password ? login() : undefined"
          />
        </label>

        <label class="block mb-4 relative">
          <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('admin.passwordLabel') }}</span>
          <input
            v-model="password"
            :type="showPw ? 'text' : 'password'"
            placeholder="••••••••"
            autocomplete="current-password"
            class="w-full px-3 py-2.5 pr-10 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:border-emerald-400"
            @keydown.enter="login"
          />
          <button
            type="button"
            class="absolute right-3 top-8 text-gray-400 hover:text-gray-600"
            @click="showPw = !showPw"
          >
            <component :is="showPw ? EyeOff : Eye" :size="16" />
          </button>
        </label>

        <button
          :disabled="loading || !email || !password"
          class="w-full py-3 rounded-xl font-black text-sm transition-opacity disabled:opacity-50"
          style="background: #065f46; color: white; font-family: inherit;"
          @click="login"
        >
          {{ loading ? t('admin.loggingIn') : t('admin.signIn') }}
        </button>

        <p class="mt-3 text-center text-xs text-gray-400">
          {{ t('admin.authorizedOnly') }}
        </p>
      </div>
    </div>
  </Teleport>
</template>
