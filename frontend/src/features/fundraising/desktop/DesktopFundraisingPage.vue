<script setup lang="ts">
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ClassicLayout from './layouts/ClassicLayout.vue'
import EditorialLayout from './layouts/EditorialLayout.vue'
import DashboardLayout from './layouts/DashboardLayout.vue'
import DDonationModal from './components/DDonationModal.vue'
import AdminFloatingBar from '@/features/admin/components/AdminFloatingBar.vue'
import { dt } from './desktopTheme'
const { c } = dt
const { t } = useI18n()

type LayoutId = 'classic' | 'editorial' | 'dashboard'

const layouts = computed<{ id: LayoutId; name: string; blurb: string }[]>(() => [
  { id: 'classic', name: t('d.switcher.classic'), blurb: t('d.switcher.classicBlurb') },
  { id: 'editorial', name: t('d.switcher.editorial'), blurb: t('d.switcher.editorialBlurb') },
  { id: 'dashboard', name: t('d.switcher.dashboard'), blurb: t('d.switcher.dashboardBlurb') },
])

const layoutMap = { classic: ClassicLayout, editorial: EditorialLayout, dashboard: DashboardLayout }

const activeLayout = ref<LayoutId>('classic')
const currentComponent = computed(() => layoutMap[activeLayout.value])

const donateState = ref<{ open: boolean; amount: number; freq: 'once' | 'monthly'; item?: string }>({
  open: false, amount: 100, freq: 'monthly',
})

function openDonate(payload: { amount: number; freq: 'once' | 'monthly'; item?: string }) {
  donateState.value = { open: true, ...payload }
}

function scrollTop() {
  window.scrollTo({ top: 0 })
}
</script>

<template>
  <div :style="{ minHeight: '100vh', background: c.bg, fontFamily: dt.font, color: c.ink }">
    <component :is="currentComponent" @donate="openDonate" />

    <!-- Layout switcher -->
    <div class="fixed left-1/2 -translate-x-1/2 bottom-5 z-[4000]" :style="{ fontFamily: dt.font }">
      <div :style="{ background: 'rgba(20,18,12,0.92)', backdropFilter: 'blur(10px)', borderRadius: '999px', padding: '6px', display: 'flex', alignItems: 'center', gap: '4px', boxShadow: '0 18px 40px -12px rgba(0,0,0,0.5)' }">
        <span :style="{ color: 'rgba(255,255,255,0.55)', fontSize: '12.5px', fontWeight: 800, padding: '0 12px 0 14px', textTransform: 'uppercase', letterSpacing: '0.05em' }">{{ t('d.switcher.version') }}</span>
        <button
          v-for="(l, i) in layouts"
          :key="l.id"
          :title="l.blurb"
          :style="{
            border: 'none', cursor: 'pointer', borderRadius: '999px', padding: '10px 18px',
            fontFamily: dt.font, fontWeight: 800, fontSize: '14.5px',
            background: activeLayout === l.id ? '#fff' : 'transparent',
            color: activeLayout === l.id ? '#1a1a1a' : 'rgba(255,255,255,0.7)',
            transition: 'all 0.15s', display: 'flex', alignItems: 'center', gap: '7px',
          }"
          @click="activeLayout = l.id; scrollTop()"
        >
          <span :style="{ opacity: activeLayout === l.id ? 0.4 : 0.35 }">{{ i + 1 }}</span>{{ l.name }}
        </button>
      </div>
    </div>

    <DDonationModal
      :open="donateState.open"
      :amount="donateState.amount"
      :freq="donateState.freq"
      :item="donateState.item"
      @close="donateState.open = false"
    />

    <AdminFloatingBar />
  </div>
</template>
