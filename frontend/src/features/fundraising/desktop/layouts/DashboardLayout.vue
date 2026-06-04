<script setup lang="ts">
import DTopNav from '../components/DTopNav.vue'
import DPill from '../components/DPill.vue'
import DDonationCard from '../components/DDonationCard.vue'
import DTrustBadges from '../components/DTrustBadges.vue'
import DSectionHeading from '../components/DSectionHeading.vue'
import DCostBar from '../components/DCostBar.vue'
import DBudgetItems from '../components/DBudgetItems.vue'
import DProgressCards from '../components/DProgressCards.vue'
import DQuote from '../components/DQuote.vue'
import DTaxBlock from '../components/DTaxBlock.vue'
import DExpenseLedger from '../components/DExpenseLedger.vue'
import DFaq from '../components/DFaq.vue'
import DFooter from '../components/DFooter.vue'
import FrIcon from '../../components/FrIcon.vue'
import { dt } from '../desktopTheme'
import DPartners from '../components/DPartners.vue'
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import { data as staticData, zl } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt

const emit = defineEmits<{
  donate: [{ amount: number; freq: 'once' | 'monthly'; item?: string }]
}>()

const siteData = inject<Ref<SiteContent>>('siteData')
const b = computed(() => siteData?.value?.budget ?? staticData.budget)
const foundation = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const statItems = computed(() => [
  ['Koszt / mies.', zl(b.value.total), c.ink],
  ['Pokrywa NFZ', zl(b.value.nfz), c.inkSoft],
  ['Brakuje / mies.', zl(b.value.gap), c.primaryDeep],
] as const)
</script>

<template>
  <div id="top">
    <DTopNav @donate="emit('donate', { amount: 100, freq: 'monthly' })" />

    <!-- Hero: facts + card -->
    <section :style="{ background: c.heroBg }">
      <div class="max-w-[1200px] mx-auto px-7 py-14" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 48px; align-items: start;">
        <div>
          <DPill tone="soft" style="margin-bottom: 18px;">Zbiórka jawna · Fundacja OPP</DPill>
          <h1 :style="{ margin: 0, fontFamily: dt.font, fontWeight: 900, fontSize: '50px', lineHeight: 1.07, letterSpacing: '-0.02em', color: c.ink }">
            Rehabilitacja Adasia kosztuje {{ zl(b.total) }} miesięcznie.
          </h1>
          <p :style="{ margin: '18px 0 26px', fontSize: '18px', lineHeight: 1.55, color: c.inkSoft, maxWidth: '540px' }">
            NFZ pokrywa ok. {{ zl(b.nfz) }}. Brakuje {{ zl(b.gap) }} co miesiąc. Składamy je z 1,5% PIT i darowizn — a każdą wydaną złotówkę pokazujemy w rozliczeniu.
          </p>
          <div class="grid grid-cols-3 gap-3.5 mb-[18px]">
            <div v-for="([l, v, col]) in statItems" :key="l" :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px', padding: '18px' }">
              <div :style="{ fontSize: '13px', color: c.inkSoft, fontWeight: 700 }">{{ l }}</div>
              <div :style="{ fontWeight: 900, color: col, fontSize: '24px', marginTop: '6px', fontFamily: dt.font }">{{ v }}</div>
            </div>
          </div>
          <DCostBar />
        </div>
        <aside style="position: sticky; top: 90px;">
          <DDonationCard @donate="emit('donate', $event)" />
        </aside>
      </div>
    </section>

    <!-- Transparentność -->
    <section id="wydatki" :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Transparentność" title="Pieniądze nie zalegają na koncie" sub="Wpłaty i wydatki w równowadze. Każda wypłata poparta fakturą z subkonta 433/L." />
        <DExpenseLedger />
      </div>
    </section>

    <!-- Budżet -->
    <section id="budzet" :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Na co zbieramy" title="Rozbicie miesięcznego budżetu" sub="Możesz sfinansować konkretną pozycję terapii." />
        <DBudgetItems :cols="3" @donate="emit('donate', $event)" />
      </div>
    </section>

    <!-- Zaufanie + Postępy -->
    <section :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Wiarygodność" title="Dlaczego możesz nam zaufać" />
        <DTrustBadges :columns="4" />
        <div :style="{ marginTop: '28px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '28px', alignItems: 'center', background: c.primarySoft, borderRadius: '18px', padding: '28px' }">
          <DQuote />
          <div class="grid gap-2.5">
            <a v-for="(l, i) in foundation.links" :key="i" href="#" :style="{ display: 'flex', alignItems: 'center', gap: '11px', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '12px', padding: '13px 16px', textDecoration: 'none' }">
              <FrIcon name="building" :size="19" :color="c.primary" />
              <span :style="{ flex: 1, fontWeight: 800, color: c.ink, fontSize: '14.5px' }">{{ l.label }}</span>
              <FrIcon name="chevR" :size="16" :color="c.inkSoft" />
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Postępy -->
    <section id="postepy" :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Dziennik" title="Co dzięki Wam osiągnęliśmy" sub="Konkretne efekty, z datami i kwotami." />
        <DProgressCards :cols="3" :limit="3" />
      </div>
    </section>

    <!-- 1.5% -->
    <section id="podatek" :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Sezon PIT · do 30 kwietnia" title="Przekaż 1,5% podatku" sub="Darmowy sposób, by wesprzeć Adasia — to część podatku, którą i tak oddajesz." />
        <DTaxBlock />
      </div>
    </section>

    <section :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :center="true" kicker="Wątpliwości?" title="Częste pytania" />
        <DFaq />
        <div class="mt-10">
          <DPartners />
        </div>
      </div>
    </section>

    <DFooter />
  </div>
</template>
