<script setup lang="ts">
import { ref } from 'vue'
import FrIcon from './FrIcon.vue'
import { theme } from '../data'
const { c, r } = theme

defineProps<{ label?: string; value: string; mono?: boolean }>()

const done = ref(false)
function copy(value: string) {
  try { navigator.clipboard?.writeText(value) } catch {}
  done.value = true
  setTimeout(() => { done.value = false }, 1500)
}
</script>

<template>
  <div class="mb-2.5">
    <div v-if="label" :style="{ fontSize: '12px', color: c.inkSoft, marginBottom: '4px', fontWeight: 600 }">{{ label }}</div>
    <button
      :style="{
        width: '100%', display: 'flex', alignItems: 'center', gap: '10px',
        padding: '11px 12px', background: c.surfaceAlt, border: `1px solid ${c.line}`,
        borderRadius: (r * 0.7) + 'px', cursor: 'pointer', textAlign: 'left',
      }"
      @click="copy(value)"
    >
      <span :style="{ flex: 1, color: c.ink, fontSize: mono !== false ? '14px' : '15px', fontWeight: 600, fontFamily: mono !== false ? `ui-monospace, Menlo, monospace` : 'inherit', wordBreak: 'break-all' }">{{ value }}</span>
      <span :style="{ display: 'inline-flex', alignItems: 'center', gap: '4px', color: done ? c.primary : c.inkSoft, fontSize: '12.5px', fontWeight: 700, flexShrink: 0 }">
        <FrIcon :name="done ? 'check' : 'copy'" :size="16" :color="done ? c.primary : c.inkSoft" />
        {{ done ? 'Skopiowano' : 'Kopiuj' }}
      </span>
    </button>
  </div>
</template>
