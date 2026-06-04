<script setup lang="ts">
import { ref } from 'vue'
import DTopNav from '../components/DTopNav.vue'
import DPh from '../components/DPh.vue'
import DPill from '../components/DPill.vue'
import DBtn from '../components/DBtn.vue'
import DSectionHeading from '../components/DSectionHeading.vue'
import DCostBar from '../components/DCostBar.vue'
import DBudgetItems from '../components/DBudgetItems.vue'
import DProgressCards from '../components/DProgressCards.vue'
import DMilestones from '../components/DMilestones.vue'
import DQuote from '../components/DQuote.vue'
import DTrustBadges from '../components/DTrustBadges.vue'
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

const freq = ref<'once' | 'monthly'>('monthly')

const siteData = inject<Ref<SiteContent>>('siteData')
const child = computed(() => siteData?.value?.child ?? staticData.child as any)
const amounts = computed(() => siteData?.value?.amounts ?? staticData.amounts)
const { mutate: patchBeneficiary } = useUpdateBeneficiary()
</script>

<template>
  <div id="top">
    <DTopNav @donate="emit('donate', { amount: 100, freq: 'monthly' })" />

    <!-- Full-bleed hero -->
    <section :style="{ background: c.heroBg, textAlign: 'center', paddingTop: '70px' }">
      <div class="max-w-[880px] mx-auto px-7">
        <DPill tone="accent" style="margin-bottom: 22px;">
          <InlineText :value="child.heroKicker" @save="patchBeneficiary({ hero_kicker: $event })" />
        </DPill>
        <InlineText
          tag="h1"
          :value="child.heroTitle"
          :multiline="true"
          @save="patchBeneficiary({ hero_title: $event })"
          :style="{ margin: 0, fontFamily: dt.font, fontWeight: 900, fontSize: '72px', lineHeight: 1.02, letterSpacing: '-0.025em', color: c.ink, display: 'block' }"
        />
        <InlineText
          tag="p"
          :value="child.heroSubtitle"
          :multiline="true"
          @save="patchBeneficiary({ hero_subtitle: $event })"
          :style="{ margin: '26px auto 0', fontSize: '21px', lineHeight: 1.55, color: c.inkSoft, maxWidth: '620px', display: 'block' }"
        />
      </div>

      <!-- Horizontal donation bar -->
      <div class="max-w-[880px] mx-auto px-7 mt-8">
        <div :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '20px', padding: '18px', display: 'flex', gap: '12px', alignItems: 'center', boxShadow: '0 24px 50px -28px rgba(60,50,20,0.4)', flexWrap: 'wrap', justifyContent: 'center' }">
          <div :style="{ display: 'flex', background: c.surfaceAlt, borderRadius: '12px', padding: '4px', gap: '4px', border: `1px solid ${c.line}` }">
            <button v-for="[k, l] in [['once', 'Jednorazowo'], ['monthly', 'Co miesiąc']]" :key="k"
              :style="{ padding: '11px 16px', border: 'none', cursor: 'pointer', borderRadius: '9px', fontFamily: dt.font, fontWeight: 800, fontSize: '14px', whiteSpace: 'nowrap', background: freq === k ? c.primary : 'transparent', color: freq === k ? c.primaryInk : c.inkSoft }"
              @click="freq = k as 'once' | 'monthly'"
            >{{ l }}</button>
          </div>
          <div class="flex gap-2">
            <button v-for="a in amounts" :key="a"
              :style="{ padding: '12px 16px', borderRadius: '11px', cursor: 'pointer', fontFamily: dt.font, border: `1.5px solid ${c.line}`, background: c.surface, color: c.ink, fontWeight: 800, fontSize: '15.5px', whiteSpace: 'nowrap' }"
              @click="emit('donate', { amount: a, freq })"
            >{{ a }} zł</button>
          </div>
          <DBtn variant="primary" size="md" @click="emit('donate', { amount: 100, freq })">
            <FrIcon name="blik" :size="18" :color="c.primaryInk" /> Wpłać BLIK-iem
          </DBtn>
        </div>
      </div>

      <!-- Wide hero image -->
      <div class="max-w-[1240px] mx-auto px-7 mt-12">
        <DPh label="Adaś" ratio="21/9" :radius="26" />
      </div>
      <div class="h-[60px]" />
    </section>

    <!-- O Adasiu — alternating -->
    <section id="o-adasiu" :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <div class="grid grid-cols-2 gap-14 items-center">
          <DPh label="Adaś z rodzicami" ratio="4/3" :radius="22" />
          <div>
            <DSectionHeading kicker="Historia" title="Wypracowuje od zera to, co innym przychodzi samo" />
            <p :style="{ margin: 0, fontSize: '17px', lineHeight: 1.7, color: c.inkSoft }">
              Pierwsze tygodnie Adaś spędził na OIOM-ie, a kilka miesięcy później usłyszeliśmy diagnozę, która zmieniła nasze życie. Dziś walczymy o jego samodzielność — krok po kroku, sesja po sesji.
            </p>
            <div class="mt-6"><DMilestones /></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Quote band -->
    <section :style="{ background: c.primary }">
      <div class="max-w-[900px] mx-auto px-7 py-[60px]">
        <DQuote :light="true" />
      </div>
    </section>

    <!-- Budżet -->
    <section id="budzet" :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :center="true" kicker="Na co zbieramy" title="Twoje 100 zł naprawdę coś znaczy" sub="Rehabilitacja Adasia kosztuje ok. 4 960 zł miesięcznie. NFZ pokrywa ok. 1 200 zł — resztę składamy razem." />
        <div class="max-w-[720px] mx-auto mb-8"><DCostBar :big="true" /></div>
        <DBudgetItems :cols="3" @donate="emit('donate', $event)" />
      </div>
    </section>

    <!-- Postępy -->
    <section id="postepy" :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Dziennik" title="Postępy Adasia" sub="Krótkie wpisy z konkretami — bo każda złotówka zamienia się w ruch." />
        <DProgressCards :cols="3" :limit="3" />
      </div>
    </section>

    <!-- 1.5% -->
    <section id="podatek" :style="{ background: c.surfaceAlt, padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading :center="true" kicker="Sezon PIT · do 30 kwietnia" title="Przekaż 1,5% podatku" />
        <DTaxBlock />
      </div>
    </section>

    <!-- Transparentność -->
    <section id="wydatki" :style="{ padding: '66px 0' }">
      <div class="max-w-[1200px] mx-auto px-7">
        <DSectionHeading kicker="Transparentność" title="Nie mamy nic do ukrycia" sub="Każda wypłata z subkonta poparta fakturą." />
        <DExpenseLedger />
        <div class="h-10" />
        <DTrustBadges :columns="4" />
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
