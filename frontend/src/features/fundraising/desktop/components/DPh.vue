<script setup lang="ts">
import { dt } from '../desktopTheme'
const { c } = dt
defineProps<{ label: string; ratio?: string; h?: number; radius?: number }>()
</script>

<template>
  <div
    :style="{
      height: ratio ? undefined : (h ?? 220) + 'px',
      aspectRatio: ratio,
      width: '100%',
      borderRadius: (radius ?? 18) + 'px',
      overflow: 'hidden',
      position: 'relative',
      background: `repeating-linear-gradient(135deg, ${c.surfaceAlt}, ${c.surfaceAlt} 13px, ${c.bg} 13px, ${c.bg} 26px)`,
      border: `1px solid ${c.line}`,
      display: 'flex', alignItems: 'center', justifyContent: 'center',
    }"
  >
    <span :style="{
      fontFamily: `ui-monospace, 'SF Mono', Menlo, monospace`, fontSize: '12px', color: c.inkSoft,
      background: c.surface, padding: '6px 11px', borderRadius: '7px', border: `1px solid ${c.line}`,
      maxWidth: '80%', textAlign: 'center', lineHeight: '1.4',
    }">{{ label }}</span>
  </div>
</template>
