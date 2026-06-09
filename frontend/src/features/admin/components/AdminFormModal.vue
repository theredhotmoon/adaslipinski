<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { X } from 'lucide-vue-next'
import { theme } from '@/features/fundraising/data'
const { c } = theme
const { t } = useI18n()

defineProps<{ title: string; open: boolean; saving?: boolean }>()
const emit = defineEmits<{ close: []; save: [] }>()
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="fixed inset-0 z-[3500] flex items-end justify-center">
      <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px]" @click="emit('close')" />
      <div
        class="relative w-full max-w-[430px] bg-white rounded-t-3xl shadow-2xl animate-sheet-up"
        style="max-height: 85vh; display: flex; flex-direction: column;"
      >
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
          <h3 class="font-black text-base" :style="{ color: c.ink, fontFamily: 'inherit' }">{{ title }}</h3>
          <button
            class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100"
            @click="emit('close')"
          ><X :size="17" /></button>
        </div>
        <div class="overflow-y-auto p-5 flex-1">
          <slot />
        </div>
        <div class="px-5 py-4 border-t border-gray-100 flex gap-3">
          <button
            class="flex-1 py-3 rounded-xl font-black text-sm transition-opacity disabled:opacity-50"
            :style="{ background: c.primary, color: c.primaryInk, fontFamily: 'inherit' }"
            :disabled="saving"
            @click="emit('save')"
          >{{ saving ? t('common.saving') : t('common.save') }}</button>
          <button
            class="px-5 py-3 rounded-xl font-bold text-sm border border-gray-200 hover:bg-gray-50"
            :style="{ fontFamily: 'inherit', color: c.inkSoft }"
            @click="emit('close')"
          >{{ t('common.cancel') }}</button>
        </div>
      </div>
    </div>
  </Teleport>
</template>
