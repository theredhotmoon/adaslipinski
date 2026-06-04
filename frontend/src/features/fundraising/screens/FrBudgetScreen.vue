<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrCard from '../components/FrCard.vue'
import FrIcon from '../components/FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import InlineNumber from '@/features/admin/components/InlineNumber.vue'
import { useUpdateBudgetItem } from '@/features/admin/useCmsApi'
import { theme, data as staticData, zl } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const emit = defineEmits<{ donate: [{ amount: number; freq: 'once' | 'monthly'; item?: string }] }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const budget = computed<SiteContent['budget']>(
  () => siteData?.value?.budget ?? {
    total: staticData.budget.total,
    nfz: staticData.budget.nfz,
    gap: staticData.budget.gap,
    items: staticData.budget.items.map((it, i) => ({ dbId: i + 1, ...it })),
  }
)
const nfzPct = computed(() => Math.round(((budget.value.nfz ?? 1200) / (budget.value.total ?? 4960)) * 100))

const { mutate: updateBudgetItem } = useUpdateBudgetItem()
</script>

<template>
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">Budżet</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">Na co dokładnie zbieramy</h1>
      <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.5 }">Konkrety, nie ogólniki. Każda pozycja to realna terapia, którą możesz sfinansować.</p>
    </div>

    <!-- Budget bar -->
    <div style="padding: 16px 18px 0;">
      <FrCard>
        <div :style="{ display: 'flex', alignItems: 'baseline', justifyContent: 'space-between', marginBottom: '4px' }">
          <span :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }">Koszt rehabilitacji / miesiąc</span>
          <span :style="{ fontWeight: 800, color: c.ink, fontSize: '17px' }">{{ zl(budget.total) }}</span>
        </div>
        <div :style="{ height: '14px', borderRadius: '8px', background: c.surfaceAlt, overflow: 'hidden', display: 'flex', margin: '10px 0' }">
          <div :style="{ width: nfzPct + '%', background: c.inkSoft }" />
          <div :style="{ flex: 1, background: c.primary }" />
        </div>
        <div :style="{ display: 'flex', justifyContent: 'space-between', fontSize: '12.5px' }">
          <span :style="{ color: c.inkSoft }"><b :style="{ color: c.ink }">{{ zl(budget.nfz) }}</b> pokrywa NFZ</span>
          <span :style="{ color: c.primary, fontWeight: 800 }">brakuje {{ zl(budget.gap) }}</span>
        </div>
      </FrCard>
    </div>

    <!-- Items -->
    <div style="padding: 16px 18px 0; display: flex; flex-direction: column; gap: 10px;">
      <FrCard v-for="it in budget.items" :key="it.id" :pad="14">
        <div :style="{ display: 'flex', gap: '12px', alignItems: 'flex-start' }">
          <div :style="{ width: '44px', height: '44px', borderRadius: '12px', background: c.primarySoft, display: 'grid', placeItems: 'center', flexShrink: 0 }">
            <FrIcon :name="it.icon" :size="24" :color="c.primary" :stroke-width="1.8" />
          </div>
          <div style="flex: 1; min-width: 0;">
            <div :style="{ display: 'flex', justifyContent: 'space-between', gap: '8px', alignItems: 'baseline', marginBottom: '2px' }">
              <InlineText
                tag="span"
                :value="it.name"
                @save="updateBudgetItem({ id: it.dbId, name: $event })"
                :style="{ fontWeight: 800, color: c.ink, fontSize: '15.5px' }"
              />
              <InlineNumber
                :value="it.cost"
                :min="0"
                @save="updateBudgetItem({ id: it.dbId, cost_pln: $event })"
                :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }"
              >{{ zl(it.cost) }}</InlineNumber>
            </div>
            <div :style="{ color: c.inkSoft, fontSize: '12px' }">{{ it.freq }} · /miesiąc</div>
            <InlineText
              tag="div"
              :value="it.note"
              :multiline="true"
              @save="updateBudgetItem({ id: it.dbId, note: $event })"
              :style="{ color: c.inkSoft, fontSize: '13px', marginTop: '6px', lineHeight: 1.45 }"
            />
            <button :style="{ marginTop: '10px', border: `1.5px solid ${c.primary}`, background: 'transparent', color: c.primary, fontWeight: 800, fontSize: '13px', padding: '8px 14px', borderRadius: (r * 0.75) + 'px', cursor: 'pointer', fontFamily: 'inherit' }" @click="emit('donate', { amount: Math.min(200, it.cost), freq: 'monthly', item: it.name })">
              Sfinansuj tę pozycję
            </button>
          </div>
        </div>
      </FrCard>
    </div>

    <!-- 100 zł card -->
    <div style="padding: 20px 18px 0;">
      <FrCard :style="{ background: c.heroBg, border: 'none' }">
        <div :style="{ fontWeight: 800, color: c.ink, fontSize: '16px', marginBottom: '10px' }">Twoje 100 zł wystarczy na:</div>
        <div v-for="([t, ic], i) in [['2× hipoterapię', 'horse'], ['1× sesję fizjoterapii', 'body'], ['tydzień terapii ręki', 'hand']]" :key="i" :style="{ display: 'flex', alignItems: 'center', gap: '10px', padding: '7px 0', color: c.ink, fontSize: '14.5px' }">
          <FrIcon :name="ic" :size="20" :color="c.primary" :stroke-width="1.8" />
          <span style="font-weight: 600;">{{ t }}</span>
          <span v-if="i < 2" :style="{ marginLeft: 'auto', fontSize: '12px', color: c.inkSoft, fontWeight: 700 }">LUB</span>
        </div>
      </FrCard>
    </div>
  </div>
</template>
