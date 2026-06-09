<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import DBtn from './DBtn.vue'
import DCopyChip from './DCopyChip.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateFoundation } from '@/features/admin/useCmsApi'
import { siteConfig } from '@/config/site'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()
const fo = siteConfig.foundation
const name = siteConfig.beneficiary.name

const siteData = inject<Ref<SiteContent>>('siteData')
const d = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const { mutate: patchFoundation } = useUpdateFoundation()
const infoNote = computed(() => t('d.tax.infoShort', { name }))

const steps = computed(() => [
  [t('tax.step1Title'), t('d.tax.step1Sub')],
  [t('tax.step2Title', { krs: fo.krs }), t('d.tax.step2Sub', { foundation: fo.name })],
  [t('tax.step3Title', { purpose: fo.purpose }), t('d.tax.step3Sub')],
])
</script>
<template>
  <div class="grid grid-cols-2 gap-7 items-center">
    <div>
      <div class="grid gap-2.5 max-w-[380px]">
        <div :style="{ display: 'flex', alignItems: 'center', gap: '10px' }">
          <span :style="{ fontSize: '12.5px', color: c.inkSoft, fontWeight: 700, minWidth: '32px' }">KRS</span>
          <InlineText :value="d.krs" @save="patchFoundation({ krs: $event })" :style="{ fontWeight: 800, color: c.ink, fontSize: '15px', fontFamily: 'ui-monospace, Menlo, monospace' }" />
        </div>
        <div :style="{ display: 'flex', alignItems: 'center', gap: '10px' }">
          <span :style="{ fontSize: '12.5px', color: c.inkSoft, fontWeight: 700, minWidth: '32px' }">{{ t('d.tax.celLabel') }}</span>
          <InlineText :value="d.cel" @save="patchFoundation({ cel: $event })" :style="{ fontWeight: 800, color: c.ink, fontSize: '16px' }" />
        </div>
        <DCopyChip label="KRS" :value="d.krs" />
        <DCopyChip :label="t('d.tax.celLabel')" :value="d.cel" :mono="false" />
      </div>
      <div class="mt-4">
        <DBtn variant="primary">
          <FrIcon name="arrowR" :size="18" :color="c.primaryInk" /> {{ t('tax.openEpit') }}
        </DBtn>
      </div>
      <div :style="{ marginTop: '14px', fontSize: '13px', color: c.inkSoft, lineHeight: 1.5, maxWidth: '420px' }" v-html="infoNote" />
    </div>
    <div class="grid gap-3">
      <div v-for="([title, s], i) in steps" :key="i" :style="{ display: 'flex', gap: '13px', alignItems: 'center', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px', padding: '14px 16px' }">
        <span :style="{ width: '32px', height: '32px', borderRadius: '999px', background: c.primary, color: c.primaryInk, display: 'grid', placeItems: 'center', fontWeight: 900, flexShrink: 0 }">{{ i + 1 }}</span>
        <div>
          <div :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }">{{ title }}</div>
          <div :style="{ color: c.inkSoft, fontSize: '13px', marginTop: '1px' }">{{ s }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
