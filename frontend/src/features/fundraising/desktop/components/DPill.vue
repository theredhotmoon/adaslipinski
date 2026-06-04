<script setup lang="ts">
import { dt } from '../desktopTheme'
const { c } = dt
const props = defineProps<{ tone?: 'accent' | 'soft' | 'primary' | 'outline' }>()
const map = {
  accent:  { bg: c.accent, fg: c.accentInk, bd: 'none' },
  soft:    { bg: c.primarySoft, fg: c.primaryDeep, bd: 'none' },
  primary: { bg: c.primary, fg: c.primaryInk, bd: 'none' },
  outline: { bg: 'transparent', fg: c.inkSoft, bd: `1px solid ${c.line}` },
}
const s = () => map[props.tone ?? 'accent']
</script>
<template>
  <span :style="{ display: 'inline-flex', alignItems: 'center', gap: '7px', background: s().bg, color: s().fg, fontSize: '13px', fontWeight: 800, padding: '6px 13px', borderRadius: '999px', border: s().bd, letterSpacing: '0.01em' }">
    <slot />
  </span>
</template>
