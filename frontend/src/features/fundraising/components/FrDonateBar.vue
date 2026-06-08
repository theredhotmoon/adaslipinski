<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import FrIcon from './FrIcon.vue'
import { theme } from '../data'
const { c, r } = theme
const { t } = useI18n()

defineProps<{ label?: string }>()
const emit = defineEmits<{ open: [] }>()
</script>

<template>
  <div :style="{ padding: '10px 14px', background: c.surface }">
    <button
      :style="{
        width: '100%', padding: '15px 20px', border: 'none', cursor: 'pointer',
        background: c.primary, color: c.primaryInk, borderRadius: r + 'px', fontFamily: 'inherit',
        fontWeight: 800, fontSize: '16.5px', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '9px',
        boxShadow: '0 10px 28px -8px rgba(0,0,0,0.5)',
      }"
      @click="emit('open')"
    >
      {{ label ?? t('donate.ctaBar') }}
      <FrIcon name="arrowR" :size="19" :color="c.primaryInk" />
    </button>
  </div>
</template>
