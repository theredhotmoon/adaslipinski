<script setup lang="ts">
import { theme } from '../data'
const { c, r } = theme

defineProps<{ label: string; h?: number; radius?: number; src?: string }>()
</script>

<template>
  <img v-if="src" :src="src" :alt="label" :style="{ height: (h ?? 180) + 'px', width: '100%', objectFit: 'cover', borderRadius: (radius ?? r) + 'px', display: 'block', border: `1px solid ${c.line}` }" />
  <div
    v-else
    :style="{
      height: (h ?? 180) + 'px',
      borderRadius: (radius ?? r) + 'px',
      overflow: 'hidden',
      position: 'relative',
      background: `repeating-linear-gradient(135deg, ${c.surfaceAlt}, ${c.surfaceAlt} 11px, ${c.bg} 11px, ${c.bg} 22px)`,
      border: `1px solid ${c.line}`,
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
    }"
  >
    <span
      :style="{
        fontFamily: `ui-monospace, 'SF Mono', Menlo, monospace`,
        fontSize: '11px',
        color: c.inkSoft,
        background: c.surface,
        padding: '5px 9px',
        borderRadius: '6px',
        border: `1px solid ${c.line}`,
        maxWidth: '82%',
        textAlign: 'center',
        lineHeight: '1.35',
      }"
    >{{ label }}</span>
  </div>
</template>
