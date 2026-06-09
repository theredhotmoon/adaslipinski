<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import AdminImageUpload from '@/features/admin/components/AdminImageUpload.vue'
import { useCreatePartner, useUpdatePartner, useDeletePartner } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()

const siteData = inject<Ref<SiteContent>>('siteData')
const partners = computed(() => siteData?.value?.partners ?? staticData.partners.map((name, i) => ({ id: i + 1, name })) as any)

const { mutate: createPartner, isPending: creating } = useCreatePartner()
const { mutate: updatePartner } = useUpdatePartner()
const { mutate: deletePartner } = useDeletePartner()

const showAdd = ref(false)
const newName = ref('')
function submit() {
  if (!newName.value) return
  createPartner({ name: newName.value }, {
    onSuccess: () => { showAdd.value = false; newName.value = '' }
  })
}
</script>
<template>
  <div class="flex gap-3 flex-wrap items-center justify-center">
    <div
      v-for="p in partners"
      :key="p.id ?? p.name"
      :style="{ padding: '13px 22px', background: c.surface, border: `1px solid ${c.line}`, borderRadius: '12px', fontWeight: 800, fontSize: '15.5px', color: c.ink, display: 'flex', alignItems: 'center', gap: '10px' }"
    >
      <img v-if="p.logoUrl" :src="p.logoUrl" :alt="p.name" style="height: 26px; width: auto; object-fit: contain;" />
      {{ typeof p === 'string' ? p : p.name }}
      <AdminImageUpload v-if="p.id" label="" :alt="p.name" @uploaded="updatePartner({ id: p.id, logo_id: $event.id })" />
      <AdminDelete v-if="p.id" :label="t('admin.del.partner')" @click="deletePartner(p.id)" />
    </div>
    <div class="max-w-[200px]">
      <AdminAdd :label="t('home.addPartner')" @click="showAdd = true" />
    </div>

    <AdminFormModal :title="t('home.addPartner')" :open="showAdd" :saving="creating" @close="showAdd = false" @save="submit">
      <label class="block">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('home.partnerNameLabel') }}</span>
        <input v-model="newName" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
    </AdminFormModal>
  </div>
</template>
