<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrIcon from '../../components/FrIcon.vue'
import DBtn from './DBtn.vue'
import DCopyChip from './DCopyChip.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateFoundation } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt

const siteData = inject<Ref<SiteContent>>('siteData')
const d = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const { mutate: patchFoundation } = useUpdateFoundation()

const steps = [
  ['Otwórz Twój e-PIT', 'podatki.gov.pl lub mObywatel'],
  ['Wpisz KRS 0000186434', 'wybierz Fundację „Słoneczko"'],
  ['Wklej cel: Adam Lipiński 433/L', 'to kluczowy krok'],
]
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
          <span :style="{ fontSize: '12.5px', color: c.inkSoft, fontWeight: 700, minWidth: '32px' }">Cel</span>
          <InlineText :value="d.cel" @save="patchFoundation({ cel: $event })" :style="{ fontWeight: 800, color: c.ink, fontSize: '16px' }" />
        </div>
        <DCopyChip label="KRS" :value="d.krs" />
        <DCopyChip label="Cel" :value="d.cel" :mono="false" />
      </div>
      <div class="mt-4">
        <DBtn variant="primary">
          <FrIcon name="arrowR" :size="18" :color="c.primaryInk" /> Otwórz Twój e-PIT
        </DBtn>
      </div>
      <div :style="{ marginTop: '14px', fontSize: '13px', color: c.inkSoft, lineHeight: 1.5, maxWidth: '420px' }">
        Cel szczegółowy <b>nie jest weryfikowany przez US</b> — jeśli go pominiesz, 1,5% trafi do fundacji, ale nie do Adasia. Wpisz go w polu <b>CEL SZCZEGÓŁOWY</b>.
      </div>
    </div>
    <div class="grid gap-3">
      <div v-for="([t, s], i) in steps" :key="i" :style="{ display: 'flex', gap: '13px', alignItems: 'center', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px', padding: '14px 16px' }">
        <span :style="{ width: '32px', height: '32px', borderRadius: '999px', background: c.primary, color: c.primaryInk, display: 'grid', placeItems: 'center', fontWeight: 900, flexShrink: 0 }">{{ i + 1 }}</span>
        <div>
          <div :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }">{{ t }}</div>
          <div :style="{ color: c.inkSoft, fontSize: '13px', marginTop: '1px' }">{{ s }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
