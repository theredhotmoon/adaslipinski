<script setup lang="ts">
import { dt } from '../desktopTheme'
const { c } = dt
defineProps<{ kicker?: string; title: string; sub?: string; center?: boolean; light?: boolean }>()
</script>
<template>
  <div
    class="mb-7"
    :style="{
      textAlign: center ? 'center' : 'left',
      maxWidth: center ? '680px' : '760px',
      marginLeft: center ? 'auto' : '0',
      marginRight: center ? 'auto' : '0',
    }"
  >
    <div v-if="kicker" :style="{ fontSize: '14px', fontWeight: 800, color: light ? c.accent : c.primary, textTransform: 'uppercase', letterSpacing: '0.06em', marginBottom: '10px' }">{{ kicker }}</div>
    <h2 :style="{ margin: 0, fontFamily: dt.font, fontWeight: 900, fontSize: '36px', lineHeight: 1.12, letterSpacing: '-0.01em', color: light ? '#fff' : c.ink }">{{ title }}</h2>
    <p v-if="sub" :style="{ margin: '12px 0 0', color: light ? 'rgba(255,255,255,0.8)' : c.inkSoft, fontSize: '17px', lineHeight: 1.55 }">{{ sub }}</p>
  </div>
</template>
