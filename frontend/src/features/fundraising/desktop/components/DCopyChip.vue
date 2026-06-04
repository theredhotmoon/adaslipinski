<script setup lang="ts">
import { ref } from 'vue'
import FrIcon from '../../components/FrIcon.vue'
import { dt } from '../desktopTheme'
const { c } = dt
const props = defineProps<{ label?: string; value: string; mono?: boolean }>()
const done = ref(false)
function copy() {
  try { navigator.clipboard?.writeText(props.value) } catch {}
  done.value = true; setTimeout(() => { done.value = false }, 1400)
}
</script>
<template>
  <button :style="{ display: 'inline-flex', alignItems: 'center', gap: '10px', padding: '11px 14px', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '12px', cursor: 'pointer', textAlign: 'left', width: '100%' }" @click="copy">
    <span v-if="label" :style="{ fontSize: '12.5px', color: c.inkSoft, fontWeight: 700 }">{{ label }}</span>
    <span :style="{ flex: 1, color: c.ink, fontSize: mono === false ? '16px' : '15px', fontWeight: 800, fontFamily: mono === false ? dt.font : 'ui-monospace, Menlo, monospace', letterSpacing: mono === false ? '0' : '0.02em' }">{{ value }}</span>
    <span :style="{ display: 'inline-flex', alignItems: 'center', gap: '5px', color: done ? c.primary : c.inkSoft, fontSize: '13px', fontWeight: 800 }">
      <FrIcon :name="done ? 'check' : 'copy'" :size="16" :color="done ? c.primary : c.inkSoft" />
      {{ done ? 'Skopiowano' : 'Kopiuj' }}
    </span>
  </button>
</template>
