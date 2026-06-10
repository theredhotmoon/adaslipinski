<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FrCard from '../components/FrCard.vue'
import FrBtn from '../components/FrBtn.vue'
import FrPill from '../components/FrPill.vue'
import FrPh from '../components/FrPh.vue'
import FrIcon from '../components/FrIcon.vue'
import FrTrustStrip from '../components/FrTrustStrip.vue'
import FrAccordion from '../components/FrAccordion.vue'
import FrFreqToggle from '../components/FrFreqToggle.vue'
import FrAmountChips from '../components/FrAmountChips.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import AdminImageUpload from '@/features/admin/components/AdminImageUpload.vue'
import {
  useUpdateBeneficiary,
  useUpdateFaq, useCreateFaq, useDeleteFaq,
  useCreatePartner, useUpdatePartner, useDeletePartner,
} from '@/features/admin/useCmsApi'
import { siteConfig } from '@/config/site'
import { theme, data as staticData, zl } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const { t } = useI18n()
const name = siteConfig.beneficiary.name

const emit = defineEmits<{ donate: [{ amount: number; freq: 'once' | 'monthly'; item?: string }]; navigate: [string] }>()

// Live data from API (provided by FundraisingPage)
const siteData = inject<Ref<SiteContent>>('siteData')
const d = computed(() => siteData?.value ?? { ...staticData, child: { ...staticData.child, heroKicker: theme.copy.heroKicker, heroTitle: theme.copy.heroTitle, heroSubtitle: theme.copy.heroSub, ctaLabel: theme.copy.cta, ctaBarLabel: theme.copy.ctaBar, recurringDefault: true } } as any)

const freq = ref<'once' | 'monthly'>('monthly')
const amount = ref(100)
const custom = ref('')
const amt = () => custom.value ? parseInt(custom.value, 10) : amount.value

const b = computed(() => d.value.budget)
const nfzPct = computed(() => Math.round(((b.value.nfz ?? 1200) / (b.value.total ?? 4960)) * 100))

// Admin mutations
const { mutate: patchBeneficiary } = useUpdateBeneficiary()
const { mutate: createFaq, isPending: addingFaq } = useCreateFaq()
const { mutate: deleteFaq } = useDeleteFaq()
const { mutate: updateFaq } = useUpdateFaq()
const { mutate: createPartner, isPending: addingPartner } = useCreatePartner()
const { mutate: updatePartner } = useUpdatePartner()
const { mutate: deletePartner } = useDeletePartner()

// FAQ add modal
const showAddFaq = ref(false)
const newFaqQ = ref('')
const newFaqA = ref('')
function submitFaq() {
  if (!newFaqQ.value || !newFaqA.value) return
  createFaq({ question: newFaqQ.value, answer: newFaqA.value }, {
    onSuccess: () => { showAddFaq.value = false; newFaqQ.value = ''; newFaqA.value = '' }
  })
}

// Partner add modal
const showAddPartner = ref(false)
const newPartnerName = ref('')
function submitPartner() {
  if (!newPartnerName.value) return
  createPartner({ name: newPartnerName.value }, {
    onSuccess: () => { showAddPartner.value = false; newPartnerName.value = '' }
  })
}

// Editable faq items with ids from API (need full objects)
const faqItems = computed(() => d.value.faq ?? staticData.faq)
</script>

<template>
  <div style="padding-bottom: 28px;">
    <!-- Hero -->
    <div :style="{ background: c.heroBg, padding: '16px 18px 22px', borderBottomLeftRadius: '26px', borderBottomRightRadius: '26px' }">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
        <FrPill tone="accent">
          <InlineText :value="d.child?.heroKicker ?? theme.copy.heroKicker" @save="patchBeneficiary({ hero_kicker: $event })" />
        </FrPill>
      </div>
      <div style="position: relative;">
        <FrPh :label="t('hero.photoAlt', { name })" :src="d.child?.heroImageUrl" :h="210" />
        <div style="position: absolute; bottom: 8px; right: 8px;">
          <AdminImageUpload :alt="t('hero.photoAlt', { name })" @uploaded="patchBeneficiary({ hero_image_id: $event.id })" />
        </div>
      </div>
      <InlineText
        tag="h1"
        :value="d.child?.heroTitle ?? theme.copy.heroTitle"
        :multiline="true"
        @save="patchBeneficiary({ hero_title: $event })"
        :style="{ margin: '16px 0 0', fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.06, letterSpacing: f.hLetter, color: c.ink, display: 'block' }"
      />
      <InlineText
        tag="p"
        :value="d.child?.heroSubtitle ?? theme.copy.heroSub"
        :multiline="true"
        @save="patchBeneficiary({ hero_subtitle: $event })"
        :style="{ margin: '12px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.55, display: 'block' }"
      />
    </div>

    <!-- Inline donate widget -->
    <div style="padding: 18px 18px 0;">
      <FrCard :pad="16" style="box-shadow: 0 8px 24px -12px rgba(0,0,0,0.18);">
        <FrFreqToggle v-model="freq" />
        <div style="height: 12px;" />
        <FrAmountChips v-model="amount" v-model:custom="custom" :freq="freq" />
        <div style="height: 12px;" />
        <FrBtn variant="primary" :full="true" size="lg" :style="{ borderRadius: r + 'px' }" @click="emit('donate', { amount: amt(), freq })">
          <FrIcon name="blik" :size="20" :color="c.primaryInk" />
          <InlineText
            :value="d.child?.ctaLabel ?? theme.copy.cta"
            @save="patchBeneficiary({ cta_label: $event })"
            :style="{ color: c.primaryInk }"
          />
          · {{ zl(amt() || 0) }}{{ freq === 'monthly' ? '/mc' : '' }}
        </FrBtn>
        <div :style="{ marginTop: '9px', textAlign: 'center', fontSize: '12px', color: c.inkSoft }">{{ t('hero.paymentMethods') }}</div>
      </FrCard>
    </div>

    <div style="padding: 16px 18px 0;"><FrTrustStrip /></div>

    <!-- Budget bar -->
    <div style="padding: 16px 18px 0;">
      <FrCard>
        <div :style="{ display: 'flex', alignItems: 'baseline', justifyContent: 'space-between', marginBottom: '4px' }">
          <span :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }">{{ t('common.monthlyCost') }}</span>
          <span :style="{ fontWeight: 800, color: c.ink, fontSize: '17px' }">{{ zl(b.total) }}</span>
        </div>
        <div :style="{ height: '14px', borderRadius: '8px', background: c.surfaceAlt, overflow: 'hidden', display: 'flex', margin: '10px 0' }">
          <div :style="{ width: nfzPct + '%', background: c.inkSoft }" />
          <div :style="{ flex: 1, background: c.primary }" />
        </div>
        <div :style="{ display: 'flex', justifyContent: 'space-between', fontSize: '12.5px' }">
          <span :style="{ color: c.inkSoft }"><b :style="{ color: c.ink }">{{ zl(b.nfz) }}</b> {{ t('common.nfzCovers') }}</span>
          <span :style="{ color: c.primary, fontWeight: 800 }">{{ t('common.gapMissing', { amount: zl(b.gap) }) }}</span>
        </div>
      </FrCard>
    </div>
    <div style="padding: 10px 18px 0;">
      <FrBtn variant="ghost" :full="true" :style="{ borderRadius: (r * 0.85) + 'px' }" @click="emit('navigate', 'budget')">
        <FrIcon name="chart" :size="17" :color="c.ink" /> {{ t('home.seeBudget') }}
      </FrBtn>
    </div>

    <!-- Latest progress -->
    <div style="padding: 24px 18px 0;">
      <div :style="{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '12px' }">
        <h2 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '21px', color: c.ink, letterSpacing: f.hLetter }">{{ t('home.whatsNew', { name }) }}</h2>
        <button :style="{ border: 'none', background: 'transparent', color: c.primary, fontWeight: 800, fontSize: '13.5px', cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: '3px', fontFamily: 'inherit' }" @click="emit('navigate', 'progress')">
          {{ t('common.all') }} <FrIcon name="chevR" :size="15" :color="c.primary" />
        </button>
      </div>
      <div :style="{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '8px' }">
        <div v-for="p in (d.progress ?? staticData.progress).slice(0, 3)" :key="p.id" style="cursor: pointer;" @click="emit('navigate', 'progress')">
          <FrPh :label="p.img" :h="84" />
          <div style="margin-top: 7px;">
            <FrPill tone="soft" style="font-size: 9.5px; padding: 3px 7px;">{{ p.tag }}</FrPill>
            <div :style="{ fontWeight: 800, color: c.ink, fontSize: '12px', marginTop: '5px', lineHeight: 1.2 }">{{ p.title }}</div>
            <div :style="{ color: c.inkSoft, fontSize: '10.5px', marginTop: '3px' }">{{ p.date }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 1.5% promo -->
    <div style="padding: 24px 18px 0;">
      <FrCard :pad="0" :style="{ overflow: 'hidden', border: `1.5px solid ${c.primary}` }">
        <div :style="{ padding: '16px', display: 'flex', gap: '12px', alignItems: 'center' }">
          <div :style="{ width: '50px', height: '50px', borderRadius: '14px', background: c.primarySoft, display: 'grid', placeItems: 'center', flexShrink: 0 }">
            <FrIcon name="percent" :size="26" :color="c.primary" />
          </div>
          <div style="flex: 1;">
            <div :style="{ fontWeight: 800, color: c.ink, fontSize: '15.5px' }">{{ t('home.taxPromoTitle') }}</div>
            <div :style="{ color: c.inkSoft, fontSize: '13px', marginTop: '2px' }">{{ t('home.taxPromoSub') }}</div>
          </div>
        </div>
        <button :style="{ width: '100%', border: 'none', borderTop: `1px solid ${c.line}`, background: c.surfaceAlt, padding: '12px', fontWeight: 800, color: c.primary, fontFamily: 'inherit', fontSize: '14px', cursor: 'pointer' }" @click="emit('navigate', 'tax')">
          {{ t('home.taxPromoCta') }}
        </button>
      </FrCard>
    </div>

    <!-- Partners -->
    <div style="padding: 24px 18px 0;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.inkSoft, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '10px' }">{{ t('home.partners') }}</div>
      <div :style="{ display: 'flex', gap: '8px', flexWrap: 'wrap', alignItems: 'center' }">
        <div
          v-for="p in d.partners"
          :key="p.id ?? p.name"
          :style="{ padding: '10px 14px', background: c.surface, border: `1px solid ${c.line}`, borderRadius: (r * 0.8) + 'px', fontWeight: 700, fontSize: '13px', color: c.ink, display: 'flex', alignItems: 'center', gap: '8px' }"
        >
          <img v-if="p.logoUrl" :src="p.logoUrl" :alt="p.name" style="height: 20px; width: auto; object-fit: contain;" />
          {{ typeof p === 'string' ? p : p.name }}
          <AdminImageUpload v-if="p.id" label="" :alt="p.name" @uploaded="updatePartner({ id: p.id, logo_id: $event.id })" />
          <AdminDelete v-if="p.id" :label="t('admin.del.partner')" @click="deletePartner(p.id)" />
        </div>
        <AdminAdd :label="t('home.addPartner')" @click="showAddPartner = true" />
      </div>
    </div>

    <!-- FAQ -->
    <div style="padding: 24px 18px 0;">
      <div :style="{ marginBottom: '12px', fontSize: '12.5px', fontWeight: 800, color: c.inkSoft, textTransform: 'uppercase', letterSpacing: '0.05em' }">{{ t('home.faqTitle') }}</div>
      <FrAccordion
        :items="faqItems"
        @edit-question="updateFaq({ id: $event.id, question: $event.value })"
        @edit-answer="updateFaq({ id: $event.id, answer: $event.value })"
        @remove="deleteFaq($event)"
      />
      <AdminAdd :label="t('home.addQuestion')" @click="showAddFaq = true" />
    </div>

    <!-- Add FAQ modal -->
    <AdminFormModal :title="t('home.addFaqTitle')" :open="showAddFaq" :saving="addingFaq" @close="showAddFaq = false" @save="submitFaq">
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('home.faqQuestionLabel') }}</span>
        <input v-model="newFaqQ" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
      <label class="block">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('home.faqAnswerLabel') }}</span>
        <textarea v-model="newFaqA" rows="4" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400 resize-none" />
      </label>
    </AdminFormModal>

    <!-- Add partner modal -->
    <AdminFormModal :title="t('home.addPartner')" :open="showAddPartner" :saving="addingPartner" @close="showAddPartner = false" @save="submitPartner">
      <label class="block">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('home.partnerNameLabel') }}</span>
        <input v-model="newPartnerName" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
    </AdminFormModal>
  </div>
</template>
