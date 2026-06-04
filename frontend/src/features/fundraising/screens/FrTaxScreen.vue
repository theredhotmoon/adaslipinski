<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrCard from '../components/FrCard.vue'
import FrBtn from '../components/FrBtn.vue'
import FrIcon from '../components/FrIcon.vue'
import FrPill from '../components/FrPill.vue'
import FrCopyField from '../components/FrCopyField.vue'
import FrSectionLabel from '../components/FrSectionLabel.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateFoundation } from '@/features/admin/useCmsApi'
import { theme, data as staticData, zl } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const siteData = inject<Ref<SiteContent>>('siteData')
const d = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const y = computed(() => siteData?.value?.yearSummary ?? staticData.yearSummary as any)

const { mutate: patchFoundation } = useUpdateFoundation()

const steps = [
  ['Otwórz Twój e-PIT', 'Zaloguj się na podatki.gov.pl lub w aplikacji mObywatel.'],
  ['Wpisz KRS 0000186434', 'W sekcji „Wniosek o przekazanie 1,5%" wybierz Fundację „Słoneczko".'],
  ['Wklej cel: Adam Lipiński 433/L', 'Bez tego pieniądze trafią do fundacji, ale nie do Adasia.'],
]
</script>

<template>
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">Sezon PIT · do 30 kwietnia</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">Przekaż 1,5% podatku</h1>
      <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.5 }">To nie wydatek — to część Twojego podatku, którą i tak oddajesz państwu. Możesz skierować ją do Adasia.</p>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrCard :style="{ background: c.heroBg, border: 'none', textAlign: 'center' }">
        <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.inkSoft, opacity: 0.85, textTransform: 'uppercase', letterSpacing: '0.05em' }">Numer KRS</div>
        <InlineText
          tag="div"
          :value="d.krs"
          @save="patchFoundation({ krs: $event })"
          :style="{ fontFamily: `ui-monospace, Menlo, monospace`, fontWeight: 800, fontSize: '30px', color: c.ink, margin: '6px 0 14px', letterSpacing: '0.02em', display: 'block' }"
        />
        <div :style="{ background: c.surface, borderRadius: (r * 0.8) + 'px', padding: '4px' }">
          <FrCopyField :value="d.krs" />
          <FrCopyField label="Cel szczegółowy — to NAJWAŻNIEJSZE pole" :value="d.cel" :mono="false" />
        </div>
      </FrCard>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrBtn variant="primary" :full="true" size="lg" :style="{ borderRadius: r + 'px' }">
        <FrIcon name="arrowR" :size="18" :color="c.primaryInk" /> Otwórz Twój e-PIT
      </FrBtn>
    </div>

    <div style="padding: 22px 18px 0;">
      <FrSectionLabel>Jak to zrobić w 3 krokach</FrSectionLabel>
      <div v-for="([t, s], i) in steps" :key="i" :style="{ display: 'flex', gap: '12px', marginBottom: '12px' }">
        <div :style="{ width: '30px', height: '30px', borderRadius: '999px', background: c.primary, color: c.primaryInk, display: 'grid', placeItems: 'center', fontWeight: 800, flexShrink: 0, fontSize: '15px' }">{{ i + 1 }}</div>
        <div>
          <div :style="{ fontWeight: 800, color: c.ink, fontSize: '14.5px' }">{{ t }}</div>
          <div :style="{ color: c.inkSoft, fontSize: '13px', marginTop: '2px', lineHeight: 1.45 }">{{ s }}</div>
        </div>
      </div>
    </div>

    <div style="padding: 8px 18px 0;">
      <FrCard :style="{ background: c.primarySoft, border: 'none', display: 'flex', gap: '10px' }">
        <FrIcon name="info" :size="22" :color="c.primary" style="flex-shrink: 0; margin-top: 1px;" />
        <div :style="{ fontSize: '13px', color: c.ink, lineHeight: 1.5 }">
          <b>Cel szczegółowy nie jest weryfikowany przez urząd skarbowy.</b> Jeśli o nim zapomnisz, 1,5% trafi do ogólnego budżetu fundacji — dlatego musi się znaleźć w polu <b>CEL SZCZEGÓŁOWY</b>.
        </div>
      </FrCard>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrSectionLabel>Co już dostaliśmy z 1,5%</FrSectionLabel>
      <FrCard :style="{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }">
        <div>
          <div :style="{ color: c.inkSoft, fontSize: '13px' }">Rok {{ y.year }}</div>
          <div :style="{ fontWeight: 800, color: c.ink, fontSize: '22px', fontFamily: `ui-monospace, Menlo, monospace` }">{{ zl(y.tax) }}</div>
        </div>
        <FrPill tone="soft">dzięki ~410 podatnikom</FrPill>
      </FrCard>
    </div>
  </div>
</template>
