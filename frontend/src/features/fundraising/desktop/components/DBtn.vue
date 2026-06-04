<script setup lang="ts">
import { dt } from '../desktopTheme'
const { c } = dt

const props = defineProps<{
  variant?: 'primary' | 'ghost' | 'soft' | 'white' | 'accent'
  size?: 'sm' | 'md' | 'lg'
  full?: boolean
}>()
const emit = defineEmits<{ click: [] }>()

const styleMap = {
  primary: { bg: c.primary, fg: c.primaryInk, bd: 'transparent', sh: `0 10px 24px -10px ${c.primary}` },
  accent:  { bg: c.accent, fg: c.accentInk, bd: 'transparent', sh: 'none' },
  ghost:   { bg: 'transparent', fg: c.ink, bd: c.line, sh: 'none' },
  soft:    { bg: c.primarySoft, fg: c.primaryDeep, bd: 'transparent', sh: 'none' },
  white:   { bg: c.surface, fg: c.ink, bd: c.line, sh: '0 4px 14px -8px rgba(0,0,0,0.3)' },
}

function getStyle() {
  const s = styleMap[props.variant ?? 'primary']
  const pad = props.size === 'lg' ? '16px 28px' : props.size === 'sm' ? '9px 16px' : '12px 22px'
  const fs = props.size === 'lg' ? 18 : props.size === 'sm' ? 14 : 16
  return {
    display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '9px',
    width: props.full ? '100%' : undefined, padding: pad, fontSize: fs + 'px', fontWeight: 800,
    fontFamily: dt.font, color: s.fg, background: s.bg, border: `1.5px solid ${s.bd}`,
    borderRadius: '14px', cursor: 'pointer', lineHeight: 1.1, boxShadow: s.sh,
    transition: 'transform 0.1s, box-shadow 0.2s',
  }
}
</script>

<template>
  <button
    :style="getStyle()"
    @click="emit('click')"
    @mouseenter="($event.currentTarget as HTMLElement).style.transform = 'translateY(-1px)'"
    @mouseleave="($event.currentTarget as HTMLElement).style.transform = 'translateY(0)'"
  >
    <slot />
  </button>
</template>
