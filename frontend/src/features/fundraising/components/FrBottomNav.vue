<script setup lang="ts">
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from './FrIcon.vue'
import { siteConfig } from '@/config/site'
import { theme } from '../data'
const { c } = theme
const { t } = useI18n()

defineProps<{ active: string }>()
const emit = defineEmits<{ nav: [string]; more: [] }>()

const items = computed(() => [
  { k: 'home', l: t('nav.home'), icon: 'home' },
  { k: 'about', l: t('nav.about', { name: siteConfig.beneficiary.name }), icon: 'heart' },
  { k: 'budget', l: t('nav.budget'), icon: 'chart' },
  { k: 'progress', l: t('nav.progress'), icon: 'news' },
  { k: 'more', l: t('nav.more'), icon: 'more' },
])
</script>

<template>
  <div :style="{ background: c.surface, borderTop: `1px solid ${c.line}`, paddingBottom: '6px', paddingTop: '8px', display: 'flex' }">
    <button
      v-for="it in items"
      :key="it.k"
      :style="{ flex: 1, border: 'none', background: 'transparent', cursor: 'pointer', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '3px', padding: '2px 0' }"
      @click="it.k === 'more' ? emit('more') : emit('nav', it.k)"
    >
      <FrIcon
        :name="it.icon"
        :size="23"
        :color="it.k !== 'more' && active === it.k ? c.navActive : c.inkSoft"
        :stroke-width="it.k !== 'more' && active === it.k ? 2.2 : 1.9"
      />
      <span :style="{ fontSize: '10.5px', fontWeight: it.k !== 'more' && active === it.k ? 800 : 600, color: it.k !== 'more' && active === it.k ? c.navActive : c.inkSoft }">
        {{ it.l }}
      </span>
    </button>
  </div>
</template>
