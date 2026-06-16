<script setup lang="ts">
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import AdminFormModal from './AdminFormModal.vue'
import { useSiteSettings, useUpdateSiteSettings } from '../useCmsApi'
import type { LayoutId } from '@/features/fundraising/types'

const props = defineProps<{ open: boolean }>()
const emit = defineEmits<{ close: [] }>()
const { t } = useI18n()

const { data, isLoading } = useSiteSettings()
const { mutateAsync, isPending } = useUpdateSiteSettings()

// Local, editable copy — committed only on Save.
const layout = ref<LayoutId>('classic')
const hidden = ref<Set<string>>(new Set())

// Seed (or re-seed) when the panel opens or fresh server data arrives.
watch(
  () => [props.open, data.value] as const,
  () => {
    if (props.open && data.value) {
      layout.value = data.value.layout
      hidden.value = new Set(data.value.hiddenSections)
    }
  },
  { immediate: true },
)

function toggleSection(key: string) {
  const next = new Set(hidden.value)
  if (next.has(key)) next.delete(key)
  else next.add(key)
  hidden.value = next
}

async function save() {
  await mutateAsync({ layout: layout.value, hiddenSections: [...hidden.value] })
  emit('close')
}
</script>

<template>
  <AdminFormModal
    :title="t('admin.settings.title')"
    :open="open"
    :saving="isPending"
    @close="emit('close')"
    @save="save"
  >
    <div v-if="isLoading" class="text-sm text-gray-400">{{ t('common.loading') }}</div>
    <template v-else>
      <!-- Active desktop layout -->
      <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">{{ t('admin.settings.layoutLabel') }}</p>
      <div class="flex flex-col gap-2 mb-6">
        <button
          v-for="id in (data?.availableLayouts ?? [])"
          :key="id"
          type="button"
          :data-testid="`settings-layout-${id}`"
          :aria-pressed="layout === id"
          class="flex items-center gap-3 px-4 py-3 rounded-xl border text-left transition-colors"
          :class="layout === id ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:bg-gray-50'"
          @click="layout = id"
        >
          <span
            class="w-4 h-4 rounded-full border-2 flex-shrink-0 mt-0.5"
            :class="layout === id ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300'"
          />
          <span class="flex-1">
            <span class="block text-sm font-bold text-gray-800">{{ t(`d.switcher.${id}`) }}</span>
            <span class="block text-xs text-gray-500">{{ t(`d.switcher.${id}Blurb`) }}</span>
          </span>
        </button>
      </div>

      <!-- Public section visibility (Astro site) -->
      <p class="text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">{{ t('admin.settings.sectionsLabel') }}</p>
      <p class="text-xs text-gray-400 mb-2">{{ t('admin.settings.sectionsHint') }}</p>
      <div class="flex flex-col gap-1.5">
        <label
          v-for="key in (data?.availableSections ?? [])"
          :key="key"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50"
        >
          <input
            type="checkbox"
            :data-testid="`settings-section-${key}`"
            class="w-4 h-4 accent-emerald-600"
            :checked="!hidden.has(key)"
            @change="toggleSection(key)"
          />
          <span class="flex-1 text-sm font-semibold text-gray-800">{{ t(`admin.settings.sections.${key}`) }}</span>
          <span v-if="hidden.has(key)" class="text-[11px] font-bold text-amber-600">{{ t('admin.settings.hidden') }}</span>
        </label>
      </div>
    </template>
  </AdminFormModal>
</template>
