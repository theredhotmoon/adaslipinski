<script setup lang="ts">
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import DBtn from './DBtn.vue'
import { siteConfig } from '@/config/site'
import { dt } from '../desktopTheme'
const { c } = dt
const { t } = useI18n()
const name = siteConfig.beneficiary.name

const emit = defineEmits<{ donate: [] }>()

const links = computed<[string, string][]>(() => [
  [t('d.nav.about', { name }), 'o-adasiu'],
  [t('d.nav.budget'), 'budzet'],
  [t('d.nav.progress'), 'postepy'],
  [t('d.nav.tax'), 'podatek'],
  [t('d.nav.transparency'), 'wydatki'],
])

function scrollTo(id: string) {
  const el = document.getElementById(id)
  if (el) window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 80, behavior: 'smooth' })
}
</script>

<template>
  <header
    class="sticky top-0 z-50"
    :style="{ background: 'rgba(254,252,245,0.88)', backdropFilter: 'blur(12px)', borderBottom: `1px solid ${c.line}` }"
  >
    <div class="max-w-[1200px] mx-auto px-7 h-[70px] flex items-center gap-6">
      <a href="#top" class="flex items-center gap-2.5 no-underline" :style="{ color: c.ink }">
        <span :style="{ width: '34px', height: '34px', borderRadius: '10px', background: c.primary, display: 'grid', placeItems: 'center' }">
          <FrIcon name="heart" :size="19" :color="c.primaryInk" />
        </span>
        <span :style="{ fontWeight: 900, fontSize: '19px', fontFamily: dt.font }">{{ siteConfig.siteName }}</span>
      </a>

      <nav class="flex gap-1 ml-3 flex-1">
        <button
          v-for="[l, id] in links"
          :key="id"
          :style="{ padding: '8px 13px', borderRadius: '9px', border: 'none', background: 'transparent', cursor: 'pointer', color: c.inkSoft, fontWeight: 700, fontSize: '14.5px', fontFamily: dt.font }"
          @click="scrollTo(id)"
          @mouseenter="($event.currentTarget as HTMLElement).style.cssText += `color:${c.ink};background:${c.surfaceAlt}`"
          @mouseleave="($event.currentTarget as HTMLElement).style.cssText += `color:${c.inkSoft};background:transparent`"
        >{{ l }}</button>
      </nav>

      <DBtn variant="primary" size="sm" @click="emit('donate')">
        <FrIcon name="heart" :size="16" :color="c.primaryInk" /> {{ t('d.nav.donate') }}
      </DBtn>
    </div>
  </header>
</template>
