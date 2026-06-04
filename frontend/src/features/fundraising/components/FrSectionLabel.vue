<script setup lang="ts">
import { theme } from '../data'
const { c, f } = theme
defineProps<{ sub?: string }>()
</script>

<template>
  <div class="mb-3">
    <h2 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '21px', letterSpacing: f.hLetter, color: c.ink, lineHeight: 1.15 }">
      <slot />
    </h2>
    <p v-if="sub" :style="{ margin: '5px 0 0', color: c.inkSoft, fontSize: '14px', lineHeight: 1.45 }">{{ sub }}</p>
  </div>
</template>
