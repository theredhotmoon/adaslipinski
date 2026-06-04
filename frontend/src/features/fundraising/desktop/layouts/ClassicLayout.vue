<script setup lang="ts">
import DTopNav from '../components/DTopNav.vue'
import DPh from '../components/DPh.vue'
import DPill from '../components/DPill.vue'
import DBtn from '../components/DBtn.vue'
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
import DPartners from '../components/DPartners.vue'
import FrIcon from '../../components/FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateBeneficiary } from '@/features/admin/useCmsApi'
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt

const emit = defineEmits<{
  donate: [{ amount: number; freq: 'once' | 'monthly'; item?: string }]
}>()

const siteData = inject<Ref<SiteContent>>('siteData')
const child = computed(() => siteData?.value?.child ?? staticData.child as any)
const foundation = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const { mutate: patchBeneficiary } = useUpdateBeneficiary()

function scrollTo(id: string) {
  const el = document.getElementById(id)
  if (el) window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 80, behavior: 'smooth' })
}
</script>

<template>
  <div id="top">
    <DTopNav @donate="emit('donate', { amount: 100, freq: 'monthly' })" />

    <!-- Hero -->
    <section :style="{ background: c.heroBg }">
      <div class="max-w-[1200px] mx-auto px-7 py-16" style="display: grid; grid-template-columns: 1.08fr 0.92fr; gap: 52px; align-items: center;">
        <div>
          <DPill tone="accent" style="margin-bottom: 18px;">
            <InlineText :value="child.heroKicker" @save="patchBeneficiary({ hero_kicker: $event })" />
          </DPill>
          <InlineText
            tag="h1"
            :value="child.heroTitle"
            :multiline="true"
            @save="patchBeneficiary({ hero_title: $event })"
            :style="{ margin: 0, fontFamily: dt.font, fontWeight: 900, fontSize: '58px', lineHeight: 1.04, letterSpacing: '-0.02em', color: c.ink, display: 'block' }"
          />
          <InlineText
            tag="p"
            :value="child.heroSubtitle"
            :multiline="true"
            @save="patchBeneficiary({ hero_subtitle: $event })"
            :style="{ margin: '22px 0 0', fontSize: '19px', lineHeight: 1.6, color: c.inkSoft, maxWidth: '480px', display: 'block' }"
          />
          <div class="flex gap-3 mt-7">
            <DBtn variant="primary" size="lg" @click="emit('donate', { amount: 100, freq: 'monthly' })">
              <FrIcon name="blik" :size="20" :color="c.primaryInk" /> Wpłać co miesiąc
            </DBtn>
            <DBtn variant="ghost" size="lg" @click="scrollTo('o-adasiu')">Poznaj historię</DBtn>
          </div>
          <div class="flex gap-[22px] items-center mt-7">
            <div :style="{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '14px', color: c.inkSoft }">
              <FrIcon name="shield" :size="18" :color="c.primary" /> Fundacja „Słoneczko" · KRS {{ foundation.krs }}
            </div>
          </div>
        </div>
        <DPh label="Zdjęcie Adasia" ratio="4/5" :radius="24" style="box-shadow: 0 30px 60px -30px rgba(60,50,20,0.5);" />
      </div>
    </section>

    <!-- Main + sticky sidebar -->
    <div class="max-w-[1200px] mx-auto px-7">
      <div style="display: grid; grid-template-columns: 1fr 360px; gap: 44px; align-items: start; padding: 56px 0;">
        <!-- Left column -->
        <div class="min-w-0">
          <div id="budzet">
            <DSectionHeading kicker="Na co zbieramy" title="Konkrety, nie ogólniki" sub="Każda pozycja to realna terapia, którą możesz sfinansować." />
            <DCostBar />
            <div class="h-4" />
            <DBudgetItems :cols="2" @donate="emit('donate', $event)" />
          </div>

          <div id="postepy" class="mt-16">
            <DSectionHeading kicker="Dziennik" title="Postępy Adasia" sub="Tu widać, że Wasze wsparcie naprawdę działa." />
            <DProgressCards :cols="2" :limit="4" />
          </div>

          <div id="o-adasiu" class="mt-16">
            <DSectionHeading kicker="Historia" title="Poznaj Adasia" />
            <div class="grid grid-cols-2 gap-[22px] items-center">
              <DPh label="Adaś podczas terapii" ratio="4/3" />
              <div :style="{ color: c.ink, fontSize: '16px', lineHeight: 1.65 }">
                <p style="margin: 0 0 12px;">Adaś urodził się z niedotlenieniem. Dziś ma 5 lat, uwielbia bajki o piesku i śmieje się na całe gardło, gdy tata podrzuca go do góry.</p>
                <p style="margin: 0;">Ale codzienność to godziny ćwiczeń — bo każdy ruch musi wypracować od zera. Wierzymy, że konsekwentna rehabilitacja da mu maksymalną samodzielność.</p>
              </div>
            </div>
            <div :style="{ marginTop: '28px', background: c.primarySoft, borderRadius: '18px', padding: '28px' }">
              <DQuote />
            </div>
          </div>

          <div id="wydatki" class="mt-16">
            <DSectionHeading kicker="Transparentność" title="Wydatki i rozliczenia" sub="Każda wypłata z subkonta = jedna faktura. Pokazujemy wszystko." />
            <DExpenseLedger />
          </div>
        </div>

        <!-- Sticky sidebar -->
        <aside style="position: sticky; top: 90px; display: grid; gap: 16px;">
          <DDonationCard @donate="emit('donate', $event)" />
          <DTrustBadges :columns="1" />
        </aside>
      </div>
    </div>

    <!-- 1.5% -->
    <section id="podatek" :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Sezon PIT · do 30 kwietnia" title="Przekaż 1,5% podatku" sub="To nie wydatek — to część Twojego podatku, którą i tak oddajesz państwu. Możesz skierować ją do Adasia." />
        <DTaxBlock />
      </div>
    </section>

    <section :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :center="true" kicker="Społeczność" title="Pomagają nam" />
        <DPartners />
      </div>
    </section>

    <section :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :center="true" kicker="Wątpliwości?" title="Częste pytania" />
        <DFaq />
      </div>
    </section>

    <DFooter />
  </div>
</template>
