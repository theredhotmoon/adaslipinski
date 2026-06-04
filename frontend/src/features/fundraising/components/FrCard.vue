<script setup lang="ts">
import { theme } from '../data'
const { c, r } = theme
defineProps<{ pad?: number; onClick?: () => void }>()
</script>

<template>
  <div
    :style="{
      background: c.surface,
      borderRadius: r + 'px',
      padding: (pad ?? 16) + 'px',
      border: `1px solid ${c.line}`,
      boxShadow: '0 1px 2px rgba(0,0,0,0.03)',
    }"
    @click="onClick"
  >
    <slot />
  </div>
</template>
