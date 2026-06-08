<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import FrIcon from './FrIcon.vue'
import { siteConfig } from '@/config/site'
import { theme } from '../data'
const { c, r } = theme
const { t } = useI18n()
const fo = siteConfig.foundation
</script>

<template>
  <div :style="{ display: 'flex', alignItems: 'center', gap: '11px', padding: '12px 14px', background: c.primarySoft, borderRadius: r + 'px', border: `1px solid ${c.line}` }">
    <FrIcon name="shield" :size="26" :color="c.primary" :stroke-width="1.8" />
    <div style="flex: 1; min-width: 0">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.ink, lineHeight: 1.3 }">
        {{ t('trust.title') }}
      </div>
      <div :style="{ fontSize: '11.5px', color: c.inkSoft, marginTop: '2px' }">
        {{ t('trust.sub', { foundation: fo.name, krs: fo.krs }) }}
      </div>
    </div>
  </div>
</template>
