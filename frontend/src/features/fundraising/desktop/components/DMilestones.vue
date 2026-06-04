<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import { useUpdateMilestone, useCreateMilestone, useDeleteMilestone } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
defineProps<{ horizontal?: boolean }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const milestones = computed<SiteContent['milestones']>(
  () => siteData?.value?.milestones ?? staticData.milestones.map((m, i) => ({ id: i + 1, year: m.year, text: m.text }))
)

const { mutate: updateMilestone } = useUpdateMilestone()
const { mutate: createMilestone, isPending: creating } = useCreateMilestone()
const { mutate: deleteMilestone } = useDeleteMilestone()

const showAdd = ref(false)
const newMs = ref({ year: '', label: '' })
function submitMilestone() {
  if (!newMs.value.year || !newMs.value.label) return
  createMilestone({ ...newMs.value }, {
    onSuccess: () => { showAdd.value = false; newMs.value = { year: '', label: '' } }
  })
}
</script>
<template>
  <!-- Horizontal timeline -->
  <div v-if="horizontal" :style="{ display: 'grid', gridTemplateColumns: `repeat(${milestones.length}, 1fr)`, gap: '16px' }">
    <div v-for="m in milestones" :key="m.id" :style="{ position: 'relative', paddingTop: '22px' }">
      <div :style="{ position: 'absolute', top: '6px', left: 0, right: 0, height: '2px', background: c.line }" />
      <div :style="{ position: 'absolute', top: 0, left: 0, width: '14px', height: '14px', borderRadius: '999px', background: c.primary, border: `3px solid ${c.primarySoft}` }" />
      <InlineText :value="m.year" @save="updateMilestone({ id: m.id, year: $event })" :style="{ fontWeight: 900, color: c.primaryDeep, fontSize: '15px' }" />
      <InlineText tag="div" :value="m.text" @save="updateMilestone({ id: m.id, label: $event })" :style="{ color: c.ink, fontSize: '15px', marginTop: '3px', lineHeight: 1.35 }" />
    </div>
  </div>
  <!-- Vertical timeline -->
  <div v-else>
    <div v-for="(m, i) in milestones" :key="m.id" class="flex gap-4">
      <div class="flex flex-col items-center">
        <div :style="{ width: '15px', height: '15px', borderRadius: '999px', background: c.primary, marginTop: '4px', border: `3px solid ${c.primarySoft}`, flexShrink: 0 }" />
        <div v-if="i < milestones.length - 1" :style="{ width: '2px', flex: 1, background: c.line, minHeight: '30px' }" />
      </div>
      <div class="pb-5 flex-1">
        <div class="flex items-center gap-2">
          <InlineText :value="m.year" @save="updateMilestone({ id: m.id, year: $event })" :style="{ fontWeight: 900, color: c.primaryDeep, fontSize: '14px' }" />
          <AdminDelete label="kamień milowy" @click="deleteMilestone(m.id)" />
        </div>
        <InlineText tag="div" :value="m.text" @save="updateMilestone({ id: m.id, label: $event })" :style="{ color: c.ink, fontSize: '16px', marginTop: '1px' }" />
      </div>
    </div>
    <div class="max-w-[280px] mt-2">
      <AdminAdd label="Dodaj kamień milowy" @click="showAdd = true" />
    </div>
  </div>

  <AdminFormModal title="Dodaj kamień milowy" :open="showAdd" :saving="creating" @close="showAdd = false" @save="submitMilestone">
    <label class="block mb-3">
      <span class="text-xs font-bold text-gray-500 mb-1 block">Rok</span>
      <input v-model="newMs.year" maxlength="4" placeholder="2026" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
    </label>
    <label class="block">
      <span class="text-xs font-bold text-gray-500 mb-1 block">Opis</span>
      <input v-model="newMs.label" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
    </label>
  </AdminFormModal>
</template>
