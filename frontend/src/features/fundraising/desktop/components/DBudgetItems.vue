<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrIcon from '../../components/FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import InlineNumber from '@/features/admin/components/InlineNumber.vue'
import { useUpdateBudgetItem } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData, zl } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt

defineProps<{ cols?: number }>()
const emit = defineEmits<{ donate: [{ amount: number; freq: 'monthly'; item: string }] }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const items = computed<SiteContent['budget']['items']>(
  () => siteData?.value?.budget.items ?? staticData.budget.items.map((it, i) => ({ dbId: i + 1, ...it }))
)

const { mutate: updateBudgetItem } = useUpdateBudgetItem()
</script>
<template>
  <div :style="{ display: 'grid', gridTemplateColumns: `repeat(${cols ?? 2}, 1fr)`, gap: '14px' }">
    <div v-for="it in items" :key="it.id" :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '16px', padding: '18px', display: 'flex', gap: '14px' }">
      <span :style="{ width: '46px', height: '46px', borderRadius: '12px', background: c.primarySoft, display: 'grid', placeItems: 'center', flexShrink: 0 }">
        <FrIcon :name="it.icon" :size="24" :color="c.primary" :stroke-width="1.8" />
      </span>
      <div class="flex-1 min-w-0">
        <div class="flex justify-between gap-2 items-baseline">
          <InlineText :value="it.name" @save="updateBudgetItem({ id: it.dbId, name: $event })" :style="{ fontWeight: 800, color: c.ink, fontSize: '16px' }" />
          <InlineNumber :value="it.cost" :min="0" @save="updateBudgetItem({ id: it.dbId, cost_pln: $event })" :style="{ fontWeight: 900, color: c.ink, fontSize: '16px', whiteSpace: 'nowrap' }">{{ zl(it.cost) }}</InlineNumber>
        </div>
        <div :style="{ fontSize: '12.5px', color: c.inkSoft, marginTop: '2px' }">{{ it.freq }} · /miesiąc</div>
        <InlineText tag="div" :value="it.note" :multiline="true" @save="updateBudgetItem({ id: it.dbId, note: $event })" :style="{ fontSize: '13.5px', color: c.inkSoft, marginTop: '7px', lineHeight: 1.45 }" />
        <button :style="{ marginTop: '11px', border: `1.5px solid ${c.primary}`, background: 'transparent', color: c.primaryDeep, fontWeight: 800, fontSize: '13.5px', padding: '8px 15px', borderRadius: '10px', cursor: 'pointer', fontFamily: dt.font }" @click="emit('donate', { amount: Math.min(200, it.cost), freq: 'monthly', item: it.name })">
          Sfinansuj tę pozycję
        </button>
      </div>
    </div>
  </div>
</template>
