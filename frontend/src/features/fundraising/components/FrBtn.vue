<script setup lang="ts">
import { theme } from '../data'
const { c, r } = theme

const props = defineProps<{
  variant?: 'primary' | 'accent' | 'ink' | 'ghost' | 'soft'
  size?: 'sm' | 'md' | 'lg'
  full?: boolean
  type?: 'button' | 'submit'
}>()

const emit = defineEmits<{ click: [] }>()

const styleMap = {
  primary: { bg: c.primary, fg: c.primaryInk, bd: 'transparent' },
  accent:  { bg: c.accent, fg: c.accentInk, bd: 'transparent' },
  ink:     { bg: c.ink, fg: c.surface, bd: 'transparent' },
  ghost:   { bg: 'transparent', fg: c.ink, bd: c.line },
  soft:    { bg: c.primarySoft, fg: c.primary, bd: 'transparent' },
}

function getStyle() {
  const s = styleMap[props.variant ?? 'primary']
  const pad = props.size === 'lg' ? '16px 22px' : props.size === 'sm' ? '8px 14px' : '12px 18px'
  const fs = props.size === 'lg' ? 17 : props.size === 'sm' ? 13.5 : 15.5
  return {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    gap: '8px',
    width: props.full ? '100%' : undefined,
    padding: pad,
    fontSize: fs + 'px',
    fontWeight: 700,
    color: s.fg,
    background: s.bg,
    border: `1.5px solid ${s.bd}`,
    borderRadius: r + 'px',
    cursor: 'pointer',
    lineHeight: 1.1,
    boxShadow: (props.variant ?? 'primary') === 'primary' ? '0 6px 16px -8px rgba(0,0,0,0.4)' : 'none',
    transition: 'transform 0.08s ease',
  }
}
</script>

<template>
  <button
    :type="type ?? 'button'"
    :style="getStyle()"
    @click="emit('click')"
    @mousedown="($event.currentTarget as HTMLElement).style.transform = 'scale(0.97)'"
    @mouseup="($event.currentTarget as HTMLElement).style.transform = 'scale(1)'"
    @mouseleave="($event.currentTarget as HTMLElement).style.transform = 'scale(1)'"
  >
    <slot />
  </button>
</template>
