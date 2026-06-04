<script setup lang="ts">
import { ref, computed, provide } from 'vue'
import { useWindowSize } from '@vueuse/core'
import AdminFloatingBar from '@/features/admin/components/AdminFloatingBar.vue'
import { useSiteContent } from '@/features/fundraising/useSiteContent'
import DesktopFundraisingPage from '@/features/fundraising/desktop/DesktopFundraisingPage.vue'
import FrBottomNav from '@/features/fundraising/components/FrBottomNav.vue'
import FrDonateBar from '@/features/fundraising/components/FrDonateBar.vue'
import FrMoreSheet from '@/features/fundraising/components/FrMoreSheet.vue'
import FrDonateModal from '@/features/fundraising/components/FrDonateModal.vue'
import FrHomeScreen from '@/features/fundraising/screens/FrHomeScreen.vue'
import FrAboutScreen from '@/features/fundraising/screens/FrAboutScreen.vue'
import FrBudgetScreen from '@/features/fundraising/screens/FrBudgetScreen.vue'
import FrProgressScreen from '@/features/fundraising/screens/FrProgressScreen.vue'
import FrTaxScreen from '@/features/fundraising/screens/FrTaxScreen.vue'
import FrExpensesScreen from '@/features/fundraising/screens/FrExpensesScreen.vue'
import FrFoundationScreen from '@/features/fundraising/screens/FrFoundationScreen.vue'
import FrContactScreen from '@/features/fundraising/screens/FrContactScreen.vue'
import { theme } from '@/features/fundraising/data'

const { c } = theme
const { width } = useWindowSize()
const isDesktop = computed(() => width.value >= 1024)

// Fetch live content from API and provide to all screens
const { data: siteData } = useSiteContent()
provide('siteData', siteData)

type Tab = 'home' | 'about' | 'budget' | 'progress' | 'tax' | 'expenses' | 'foundation' | 'contact'

const tab = ref<Tab>('home')
const moreOpen = ref(false)
const donateOpen = ref(false)
const donateInit = ref({ amount: 100, freq: 'monthly' as 'once' | 'monthly', item: null as string | null })

const screenEl = ref<HTMLElement | null>(null)

const screenMap: Record<Tab, unknown> = {
  home: FrHomeScreen,
  about: FrAboutScreen,
  budget: FrBudgetScreen,
  progress: FrProgressScreen,
  tax: FrTaxScreen,
  expenses: FrExpensesScreen,
  foundation: FrFoundationScreen,
  contact: FrContactScreen,
}

const currentScreen = computed(() => screenMap[tab.value])

function navigate(t: string) {
  tab.value = t as Tab
  moreOpen.value = false
  screenEl.value?.scrollTo({ top: 0 })
}

function openDonate(payload: { amount: number; freq: 'once' | 'monthly'; item?: string }) {
  donateInit.value = { amount: payload.amount, freq: payload.freq, item: payload.item ?? null }
  donateOpen.value = true
}
</script>

<template>
  <DesktopFundraisingPage v-if="isDesktop" />
  <div
    v-else
    :style="{
      maxWidth: '430px',
      margin: '0 auto',
      minHeight: '100svh',
      display: 'flex',
      flexDirection: 'column',
      background: c.bg,
      fontFamily: `'Nunito', system-ui, sans-serif`,
      color: c.ink,
      position: 'relative',
    }"
  >
    <!-- Scrollable content -->
    <div
      ref="screenEl"
      style="flex: 1; overflow-y: auto; overflow-x: hidden;"
    >
      <component
        :is="currentScreen"
        @donate="openDonate"
        @navigate="navigate"
      />
      <!-- Bottom padding so content isn't hidden behind sticky bar -->
      <div style="height: 120px;" />
    </div>

    <!-- Sticky bottom -->
    <div
      :style="{
        position: 'sticky',
        bottom: 0,
        zIndex: 80,
        background: c.surface,
      }"
    >
      <FrDonateBar :label="theme.copy.ctaBar" @open="openDonate({ amount: 100, freq: 'monthly' })" />
      <FrBottomNav :active="tab" @nav="navigate" @more="moreOpen = true" />
      <div :style="{ display: 'flex', justifyContent: 'center', paddingBottom: '8px', paddingTop: '2px', background: c.surface }">
        <div :style="{ width: '134px', height: '5px', borderRadius: '3px', background: 'rgba(0,0,0,0.18)' }" />
      </div>
    </div>

    <AdminFloatingBar />
    <FrMoreSheet :open="moreOpen" @close="moreOpen = false" @navigate="navigate" />
    <FrDonateModal
      :open="donateOpen"
      :init-amount="donateInit.amount"
      :init-freq="donateInit.freq"
      :init-item="donateInit.item"
      @close="donateOpen = false"
    />
  </div>
</template>
