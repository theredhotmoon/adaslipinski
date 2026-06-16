<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '@/features/auth/store'
import { theme } from '@/features/fundraising/data'
const { c } = theme
const { t } = useI18n()

defineProps<{ label?: string; testid?: string }>()
const emit = defineEmits<{ click: [] }>()

const auth = useAuthStore()
</script>

<template>
  <button
    v-if="auth.isAuthenticated"
    :data-testid="testid"
    :style="{
      display: 'flex', alignItems: 'center', gap: '6px',
      padding: '8px 14px', border: `1.5px dashed ${c.primary}`,
      borderRadius: '10px', background: c.primarySoft, color: c.primary,
      fontFamily: 'inherit', fontWeight: 700, fontSize: '13px', cursor: 'pointer',
      width: '100%', justifyContent: 'center', marginTop: '8px',
    }"
    @click="emit('click')"
  >
    <span style="font-size: 18px; line-height: 1;">+</span>
    {{ label ?? t('admin.add') }}
  </button>
</template>
