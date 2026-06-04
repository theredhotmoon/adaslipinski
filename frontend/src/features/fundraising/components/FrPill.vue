<script setup lang="ts">
import { theme } from '../data'
const { c } = theme

const props = defineProps<{ tone?: 'primary' | 'accent' | 'soft' }>()

function getStyle() {
  const tone = props.tone ?? 'primary'
  const bg = tone === 'accent' ? c.accent : tone === 'soft' ? c.primarySoft : c.primary
  const fg = tone === 'accent' ? c.accentInk : tone === 'soft' ? c.primary : c.primaryInk
  return { display: 'inline-flex', alignItems: 'center', gap: '5px', background: bg, color: fg,
    fontSize: '11.5px', fontWeight: 700, padding: '4px 10px', borderRadius: '999px', letterSpacing: '0.01em' }
}
</script>

<template>
  <span :style="getStyle()"><slot /></span>
</template>
