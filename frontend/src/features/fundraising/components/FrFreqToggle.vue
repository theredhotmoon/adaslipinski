<script setup lang="ts">
import FrIcon from './FrIcon.vue'
import { theme } from '../data'
const { c, r } = theme

const props = defineProps<{ modelValue: 'once' | 'monthly'; size?: 'md' | 'lg' }>()
const emit = defineEmits<{ 'update:modelValue': ['once' | 'monthly'] }>()

const opts = [
  { k: 'once' as const, l: 'Jednorazowo' },
  { k: 'monthly' as const, l: 'Co miesiąc' },
]
</script>

<template>
  <div :style="{ display: 'flex', background: c.surfaceAlt, borderRadius: r + 'px', padding: '4px', gap: '4px', border: `1px solid ${c.line}` }">
    <button
      v-for="o in opts"
      :key="o.k"
      :style="{
        flex: 1, padding: size === 'lg' ? '12px 8px' : '9px 8px', border: 'none', cursor: 'pointer',
        borderRadius: (r * 0.72) + 'px', fontFamily: 'inherit', fontWeight: 700, fontSize: '14px',
        background: modelValue === o.k ? c.primary : 'transparent',
        color: modelValue === o.k ? c.primaryInk : c.inkSoft,
        display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '6px',
        boxShadow: modelValue === o.k ? '0 4px 12px -6px rgba(0,0,0,0.5)' : 'none',
        transition: 'all 0.15s',
      }"
      @click="emit('update:modelValue', o.k)"
    >
      <FrIcon v-if="o.k === 'monthly'" name="repeat" :size="15" :color="modelValue === o.k ? c.primaryInk : c.inkSoft" />
      {{ o.l }}
    </button>
  </div>
</template>
