<script setup lang="ts">
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from './FrIcon.vue'
import { siteConfig } from '@/config/site'
import { theme } from '../data'
const { c, r } = theme
const { t } = useI18n()

defineProps<{ open: boolean }>()
const emit = defineEmits<{ close: []; navigate: [string] }>()

const items = computed(() => [
  { k: 'tax', l: t('nav.tax'), s: t('moreSheet.taxS'), icon: 'percent' },
  { k: 'expenses', l: t('nav.expenses'), s: t('moreSheet.expensesS'), icon: 'receipt' },
  { k: 'foundation', l: t('nav.foundation'), s: t('moreSheet.foundationS'), icon: 'building' },
  { k: 'contact', l: t('nav.contact'), s: t('moreSheet.contactS'), icon: 'mail' },
])

const footer = computed(() =>
  t('moreSheet.footer', { foundation: siteConfig.foundation.name, krs: siteConfig.foundation.krs }),
)
</script>

<template>
  <Teleport to="body">
    <div v-if="open" style="position: fixed; inset: 0; z-index: 1500; display: flex; flex-direction: column; justify-content: flex-end;">
      <div style="position: absolute; inset: 0; background: rgba(20,20,25,0.4);" @click="emit('close')" />
      <div
        :style="{
          position: 'relative', background: c.bg, borderTopLeftRadius: '26px', borderTopRightRadius: '26px',
          padding: '10px 16px 28px', width: '100%', maxWidth: '430px', margin: '0 auto',
        }"
        class="animate-sheet-up"
      >
        <div style="display: flex; justify-content: center; padding-bottom: 12px;">
          <div :style="{ width: '40px', height: '5px', borderRadius: '9px', background: c.line }" />
        </div>
        <div class="flex flex-col gap-2">
          <button
            v-for="it in items"
            :key="it.k"
            :style="{
              display: 'flex', alignItems: 'center', gap: '13px', padding: '14px',
              background: c.surface, border: `1px solid ${c.line}`, borderRadius: r + 'px',
              cursor: 'pointer', fontFamily: 'inherit', textAlign: 'left',
            }"
            @click="emit('close'); emit('navigate', it.k)"
          >
            <div :style="{ width: '42px', height: '42px', borderRadius: '11px', background: c.primarySoft, display: 'grid', placeItems: 'center', flexShrink: 0 }">
              <FrIcon :name="it.icon" :size="22" :color="c.primary" />
            </div>
            <div style="flex: 1;">
              <div :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }">{{ it.l }}</div>
              <div :style="{ color: c.inkSoft, fontSize: '12.5px', marginTop: '1px' }">{{ it.s }}</div>
            </div>
            <FrIcon name="chevR" :size="18" :color="c.inkSoft" />
          </button>
        </div>
        <div :style="{ marginTop: '16px', textAlign: 'center', fontSize: '11.5px', color: c.inkSoft, lineHeight: 1.5 }" v-html="footer" />
      </div>
    </div>
  </Teleport>
</template>
