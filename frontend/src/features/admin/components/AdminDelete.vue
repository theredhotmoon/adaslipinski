<script setup lang="ts">
import { useAuthStore } from '@/features/auth/store'
import { X } from 'lucide-vue-next'

const props = defineProps<{ label?: string }>()
const emit = defineEmits<{ click: [] }>()
const auth = useAuthStore()

function handleClick() {
  if (confirm(`Usunąć ${props.label ?? 'ten element'}?`)) emit('click')
}
</script>

<template>
  <button
    v-if="auth.isAuthenticated"
    :style="{
      display: 'inline-flex', alignItems: 'center', gap: '4px',
      padding: '4px 8px', border: '1px solid #fca5a5',
      borderRadius: '6px', background: '#fff1f2', color: '#ef4444',
      fontFamily: 'inherit', fontWeight: 700, fontSize: '12px', cursor: 'pointer',
    }"
    @click.stop="handleClick"
  >
    <X :size="13" /> {{ label ?? 'Usuń' }}
  </button>
</template>
