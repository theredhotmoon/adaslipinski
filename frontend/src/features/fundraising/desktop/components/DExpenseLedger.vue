<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import { useCreateExpense, useDeleteExpense } from '@/features/admin/useCmsApi'
import { useAuthStore } from '@/features/auth/store'
import { dt } from '../desktopTheme'
import { data as staticData, zl } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()
const auth = useAuthStore()

const siteData = inject<Ref<SiteContent>>('siteData')
const expenses = computed(() => siteData?.value?.expenses ?? staticData.expenses as any)
const y = computed(() => siteData?.value?.yearSummary ?? staticData.yearSummary as any)
const summaryItems = computed(() => [
  [t('d.ledger.receivedIn', { year: y.value.year }), zl(y.value.in), c.primary],
  [t('expenses.spent'), zl(y.value.out), c.ink],
  [t('expenses.left'), zl(y.value.left), c.inkSoft],
  [t('d.ledger.fromTax'), zl(y.value.tax), c.primaryDeep],
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
  <div>
    <div class="grid grid-cols-4 gap-3.5 mb-[18px]">
      <div v-for="([l, v, col]) in summaryItems" :key="l" :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px', padding: '18px' }">
        <div :style="{ fontSize: '13px', color: c.inkSoft, fontWeight: 600 }">{{ l }}</div>
        <div :style="{ fontWeight: 900, color: col, fontSize: '22px', marginTop: '6px', fontFamily: dt.font }">{{ v }}</div>
      </div>
    </div>
    <div :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '16px', overflow: 'hidden' }">
      <div :style="{ display: 'grid', gridTemplateColumns: '100px 1fr 150px 110px 90px', gap: '12px', padding: '13px 20px', background: c.surfaceAlt, fontSize: '12.5px', fontWeight: 800, color: c.inkSoft, textTransform: 'uppercase', letterSpacing: '0.03em' }">
        <span>{{ t('d.ledger.colDate') }}</span><span>{{ t('d.ledger.colDesc') }}</span><span>{{ t('d.ledger.colVendor') }}</span><span class="text-right">{{ t('d.ledger.colAmount') }}</span><span class="text-right">{{ auth.isAuthenticated ? '' : t('d.ledger.colInvoice') }}</span>
      </div>
      <div v-for="e in expenses" :key="e.id" :style="{ display: 'grid', gridTemplateColumns: '100px 1fr 150px 110px 90px', gap: '12px', padding: '14px 20px', borderTop: `1px solid ${c.line}`, alignItems: 'center', fontSize: '14.5px' }">
        <span :style="{ color: c.inkSoft, fontFamily: 'ui-monospace, Menlo, monospace', fontSize: '13px' }">{{ e.date }}</span>
        <span :style="{ color: c.ink, fontWeight: 700 }">{{ e.desc }}</span>
        <span :style="{ color: c.inkSoft, fontSize: '13.5px' }">{{ e.place }}</span>
        <span class="text-right" :style="{ fontWeight: 800, color: c.ink, fontFamily: 'ui-monospace, Menlo, monospace' }">{{ zl(e.amount) }}</span>
        <span class="text-right">
          <a v-if="!auth.isAuthenticated" href="#" :style="{ color: c.primaryDeep, fontWeight: 800, fontSize: '13px', textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '3px' }">
            <FrIcon name="receipt" :size="14" :color="c.primaryDeep" />PDF
          </a>
          <AdminDelete v-else :label="t('admin.del.expense')" @click="deleteExpense(e.id)" />
        </span>
      </div>
    </div>
    <div class="max-w-[300px] mt-3">
      <AdminAdd :label="t('expenses.addExpense')" @click="showAdd = true" />
    </div>

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
