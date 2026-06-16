<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { LogOut, Pencil, Settings } from 'lucide-vue-next'
import { useAuthStore } from '@/features/auth/store'
import AdminSiteSettings from './AdminSiteSettings.vue'

const auth = useAuthStore()
const router = useRouter()
const { t } = useI18n()

// Site Settings panel
const showSettings = ref(false)

function logout() {
  auth.logout()
  router.push('/login')
}
</script>

<template>
  <!-- Admin toolbar — the whole app is auth-gated, so this only renders for the signed-in admin -->
  <div
    v-if="auth.isAuthenticated"
    data-testid="admin-toolbar"
    class="fixed bottom-[130px] right-3 z-[2000] flex flex-col items-end gap-1"
  >
    <div
      class="flex items-center gap-2 px-3 py-2 rounded-full shadow-lg text-xs font-bold"
      style="background: #065f46; color: #d1fae5; font-family: inherit;"
    >
      <Pencil :size="13" />
      {{ t('admin.mode') }}
      <button
        @click="showSettings = true"
        data-testid="admin-settings-btn"
        class="ml-1 opacity-70 hover:opacity-100 transition-opacity"
        :title="t('admin.settings.title')"
      >
        <Settings :size="13" />
      </button>
      <button
        @click="logout"
        data-testid="admin-logout"
        class="opacity-70 hover:opacity-100 transition-opacity"
        :title="t('admin.logout')"
      >
        <LogOut :size="13" />
      </button>
    </div>
    <div class="text-[10px] text-white/60 pr-1">{{ auth.user?.email }}</div>
  </div>

  <!-- Site Settings panel -->
  <AdminSiteSettings v-if="auth.isAuthenticated" :open="showSettings" @close="showSettings = false" />
</template>
