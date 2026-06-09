<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FrCard from '../components/FrCard.vue'
import FrBtn from '../components/FrBtn.vue'
import FrIcon from '../components/FrIcon.vue'
import FrSectionLabel from '../components/FrSectionLabel.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import { useCreateExpense, useDeleteExpense } from '@/features/admin/useCmsApi'
import { useAuthStore } from '@/features/auth/store'
import { theme, data as staticData, zl } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const { t } = useI18n()
const auth = useAuthStore()

const siteData = inject<Ref<SiteContent>>('siteData')
const expenses = computed(() => siteData?.value?.expenses ?? staticData.expenses as any)
const y = computed(() => siteData?.value?.yearSummary ?? staticData.yearSummary as any)

const summaryItems = computed(() => [
  [t('expenses.received'), y.value.in, c.primary],
  [t('expenses.spent'), y.value.out, c.ink],
  [t('expenses.left'), y.value.left, c.inkSoft],
] as const)

const { mutate: createExpense, isPending: creating } = useCreateExpense()
const { mutate: deleteExpense } = useDeleteExpense()

const showAdd = ref(false)
const newExp = ref({ expense_date: '', description: '', amount_pln: '', vendor: '' })
function submitExpense() {
  if (!newExp.value.description || !newExp.value.amount_pln) return
  createExpense({
    expense_date: newExp.value.expense_date || new Date().toISOString().slice(0, 10),
    description: newExp.value.description,
    amount_pln: parseInt(newExp.value.amount_pln),
    vendor: newExp.value.vendor,
    has_invoice: true,
  }, {
    onSuccess: () => { showAdd.value = false; newExp.value = { expense_date: '', description: '', amount_pln: '', vendor: '' } }
  })
}

</script>

<template>
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">{{ t('expenses.kicker') }}</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">{{ t('expenses.title') }}</h1>
      <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.5 }">{{ t('expenses.subtitle') }}</p>
    </div>

    <div style="padding: 16px 18px 0;">
      <div :style="{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '8px' }">
        <FrCard v-for="([l, v, col]) in summaryItems" :key="l" :pad="12" style="text-align: center;">
          <div :style="{ fontSize: '11.5px', color: c.inkSoft, fontWeight: 600 }">{{ l }}</div>
          <div :style="{ fontWeight: 800, color: col, fontSize: '15px', marginTop: '4px', fontFamily: `ui-monospace, Menlo, monospace` }">{{ (v / 1000).toFixed(1) }}k</div>
        </FrCard>
      </div>
      <div :style="{ textAlign: 'center', fontSize: '12px', color: c.inkSoft, marginTop: '8px' }">{{ t('expenses.summary', { year: y.year }) }}</div>
    </div>

    <div style="padding: 18px 18px 0;">
      <FrSectionLabel>{{ t('expenses.recentPayouts') }}</FrSectionLabel>
      <FrCard :pad="0" :style="{ overflow: 'hidden' }">
        <div v-for="(e, i) in expenses" :key="i" :style="{ padding: '13px 14px', borderTop: i ? `1px solid ${c.line}` : 'none', display: 'flex', gap: '10px', alignItems: 'center' }">
          <div style="flex: 1; min-width: 0;">
            <div :style="{ fontWeight: 700, color: c.ink, fontSize: '14px', lineHeight: 1.3 }">{{ e.desc }}</div>
            <div :style="{ color: c.inkSoft, fontSize: '12px', marginTop: '2px' }">{{ e.date }} · {{ e.place }}</div>
          </div>
          <div style="text-align: right; flex-shrink: 0;">
            <div :style="{ fontWeight: 800, color: c.ink, fontSize: '14px', fontFamily: `ui-monospace, Menlo, monospace` }">{{ zl(e.amount) }}</div>
            <button v-if="!auth.isAuthenticated" :style="{ marginTop: '3px', border: 'none', background: 'transparent', color: c.primary, fontWeight: 800, fontSize: '11.5px', cursor: 'pointer', fontFamily: 'inherit', display: 'inline-flex', alignItems: 'center', gap: '2px' }">
              <FrIcon name="receipt" :size="13" :color="c.primary" /> {{ t('expenses.invoicePdf') }}
            </button>
            <div v-else style="margin-top: 4px;">
              <AdminDelete :label="t('admin.del.expense')" @click="deleteExpense(e.id)" />
            </div>
          </div>
        </div>
      </FrCard>
      <AdminAdd :label="t('expenses.addExpense')" @click="showAdd = true" />
    </div>

    <div style="padding: 16px 18px 0; display: flex; flex-direction: column; gap: 8px;">
      <FrBtn variant="ghost" :full="true" :style="{ borderRadius: (r * 0.85) + 'px' }">
        <FrIcon name="chart" :size="17" :color="c.ink" /> {{ t('expenses.downloadCsv') }}
      </FrBtn>
      <FrBtn variant="ghost" :full="true" :style="{ borderRadius: (r * 0.85) + 'px' }">
        <FrIcon name="shield" :size="17" :color="c.ink" /> {{ t('expenses.oppReports') }}
      </FrBtn>
    </div>

    <!-- Add expense modal -->
    <AdminFormModal :title="t('expenses.addExpense')" :open="showAdd" :saving="creating" @close="showAdd = false" @save="submitExpense">
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('expenses.descLabel') }}</span>
        <input v-model="newExp.description" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <label class="block">
          <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('expenses.amountLabel') }}</span>
          <input v-model="newExp.amount_pln" type="number" min="0" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
        </label>
        <label class="block">
          <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('expenses.dateLabel') }}</span>
          <input v-model="newExp.expense_date" type="date" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
        </label>
      </div>
      <label class="block">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('expenses.vendorLabel') }}</span>
        <input v-model="newExp.vendor" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
    </AdminFormModal>
  </div>
</template>
