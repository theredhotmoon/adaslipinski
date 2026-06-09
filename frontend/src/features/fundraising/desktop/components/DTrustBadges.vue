<script setup lang="ts">
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import { siteConfig } from '@/config/site'
import { dt } from '../desktopTheme'
const { c } = dt
const { t } = useI18n()
const fo = siteConfig.foundation
defineProps<{ columns?: number }>()

const items = computed(() => [
  ['shield', t('d.trust.oppTitle'), `KRS ${fo.krs}`],
  ['building', t('d.trust.subaccount'), fo.purpose],
  ['receipt', t('d.trust.invoiceTitle'), t('d.trust.invoiceSub')],
  ['chart', t('d.trust.reportsTitle'), t('d.trust.reportsSub')],
] as const)
</script>
<template>
  <div :style="{ display: 'grid', gridTemplateColumns: `repeat(${columns ?? 4}, 1fr)`, gap: '14px' }">
    <div v-for="([ic, title, sub], i) in items" :key="i" :style="{ display: 'flex', gap: '11px', alignItems: 'center', padding: '14px 16px', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px' }">
      <span :style="{ width: '38px', height: '38px', borderRadius: '10px', background: c.primarySoft, display: 'grid', placeItems: 'center', flexShrink: 0 }">
        <FrIcon :name="ic" :size="20" :color="c.primary" :stroke-width="1.9" />
      </span>
      <div class="min-w-0">
        <div :style="{ fontWeight: 800, fontSize: '14px', color: c.ink }">{{ title }}</div>
        <div :style="{ fontSize: '12.5px', color: c.inkSoft, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }">{{ sub }}</div>
      </div>
    </div>
  </div>
</template>
