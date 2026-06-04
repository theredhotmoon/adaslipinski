<script setup lang="ts">
import FrIcon from '../../components/FrIcon.vue'
import { dt } from '../desktopTheme'
import { data } from '../../data'
const { c } = dt
defineProps<{ columns?: number }>()

const items = [
  ['shield', 'Fundacja OPP', `KRS ${data.foundation.krs}`],
  ['building', 'Subkonto', data.foundation.cel],
  ['receipt', 'Każda faktura', 'jawna w rejestrze'],
  ['chart', 'Sprawozdania', 'w bazie NIW'],
] as const
</script>
<template>
  <div :style="{ display: 'grid', gridTemplateColumns: `repeat(${columns ?? 4}, 1fr)`, gap: '14px' }">
    <div v-for="([ic, t, s], i) in items" :key="i" :style="{ display: 'flex', gap: '11px', alignItems: 'center', padding: '14px 16px', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px' }">
      <span :style="{ width: '38px', height: '38px', borderRadius: '10px', background: c.primarySoft, display: 'grid', placeItems: 'center', flexShrink: 0 }">
        <FrIcon :name="ic" :size="20" :color="c.primary" :stroke-width="1.9" />
      </span>
      <div class="min-w-0">
        <div :style="{ fontWeight: 800, fontSize: '14px', color: c.ink }">{{ t }}</div>
        <div :style="{ fontSize: '12.5px', color: c.inkSoft, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }">{{ s }}</div>
      </div>
    </div>
  </div>
</template>
