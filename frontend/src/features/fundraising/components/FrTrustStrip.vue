<script setup lang="ts">
import FrIcon from './FrIcon.vue'
import { theme, data } from '../data'
const { c, r } = theme
const d = data.foundation
</script>

<template>
  <div :style="{ display: 'flex', alignItems: 'center', gap: '11px', padding: '12px 14px', background: c.primarySoft, borderRadius: r + 'px', border: `1px solid ${c.line}` }">
    <FrIcon name="shield" :size="26" :color="c.primary" :stroke-width="1.8" />
    <div style="flex: 1; min-width: 0">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.ink, lineHeight: 1.3 }">
        Pieniądze trafiają do fundacji, nie na konto prywatne.
      </div>
      <div :style="{ fontSize: '11.5px', color: c.inkSoft, marginTop: '2px' }">
        Fundacja „Słoneczko" · OPP · KRS {{ d.krs }}
      </div>
    </div>
  </div>
</template>
