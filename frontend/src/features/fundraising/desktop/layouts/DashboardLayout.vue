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
import { useI18n } from 'vue-i18n'
import { siteConfig } from '@/config/site'
import { data as staticData, zl } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()
const name = siteConfig.beneficiary.name

const emit = defineEmits<{
  donate: [{ amount: number; freq: 'once' | 'monthly'; item?: string }]
}>()

const siteData = inject<Ref<SiteContent>>('siteData')
const b = computed(() => siteData?.value?.budget ?? staticData.budget)
const foundation = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const statItems = computed(() => [
  [t('d.dashboard.statCost'), zl(b.value.total), c.ink],
  [t('d.dashboard.statNfz'), zl(b.value.nfz), c.inkSoft],
  [t('d.dashboard.statGap'), zl(b.value.gap), c.primaryDeep],
] as const)
</script>

<template>
  <div id="top">
    <DTopNav @donate="emit('donate', { amount: 100, freq: 'monthly' })" />

    <!-- Hero: facts + card -->
    <section :style="{ background: c.heroBg }">
      <div class="max-w-[1200px] mx-auto px-7 py-14" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 48px; align-items: start;">
        <div>
          <DPill tone="soft" style="margin-bottom: 18px;">{{ t('d.dashboard.heroPill') }}</DPill>
          <h1 :style="{ margin: 0, fontFamily: dt.font, fontWeight: 900, fontSize: '50px', lineHeight: 1.07, letterSpacing: '-0.02em', color: c.ink }">
            {{ t('d.dashboard.heroTitle', { name, total: zl(b.total) }) }}
          </h1>
          <p :style="{ margin: '18px 0 26px', fontSize: '18px', lineHeight: 1.55, color: c.inkSoft, maxWidth: '540px' }">
            {{ t('d.dashboard.heroSub', { nfz: zl(b.nfz), gap: zl(b.gap) }) }}
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
        <DSectionHeading :kicker="t('expenses.kicker')" :title="t('d.dashboard.expensesTitle')" :sub="t('d.dashboard.expensesSub')" />
        <DExpenseLedger />
      </div>
    </section>

    <!-- Budżet -->
    <section id="budzet" :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :kicker="t('d.kicker.budget')" :title="t('d.dashboard.budgetTitle')" :sub="t('d.dashboard.budgetSub')" />
        <DBudgetItems :cols="3" @donate="emit('donate', $event)" />
      </div>
    </section>

    <!-- Zaufanie + Postępy -->
    <section :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :kicker="t('d.kicker.credibility')" :title="t('d.dashboard.trustTitle')" />
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
        <DSectionHeading :kicker="t('progress.kicker')" :title="t('d.dashboard.progressTitle')" :sub="t('d.dashboard.progressSub')" />
        <DProgressCards :cols="3" :limit="3" />
      </div>
    </section>

    <!-- 1.5% -->
    <section id="podatek" :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :kicker="t('tax.kicker')" :title="t('tax.title')" :sub="t('d.dashboard.taxSub', { name })" />
        <DTaxBlock />
      </div>
    </section>

    <section :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :center="true" :kicker="t('d.kicker.faq')" :title="t('home.faqTitle')" />
        <DFaq />
        <div class="mt-10">
          <DPartners />
        </div>
      </div>
    </section>

    <DFooter />
  </div>
</template>
