<script setup lang="ts">
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import DBtn from './DBtn.vue'
import { siteConfig } from '@/config/site'
import { dt } from '../desktopTheme'
import { data, zl } from '../../data'
const { c } = dt
const { t } = useI18n()
const fo = siteConfig.foundation

const emit = defineEmits<{ donate: [{ amount: number; freq: 'once' | 'monthly' }] }>()

const freq = ref<'once' | 'monthly'>('monthly')
const amount = ref(100)
const custom = ref('')
const amt = () => custom.value ? parseInt(custom.value, 10) : amount.value

const opts = computed(() => [
  { k: 'once' as const, l: t('donate.once') },
  { k: 'monthly' as const, l: t('donate.monthly') },
])
</script>
<template>
  <div :style="{ background: c.surface, borderRadius: '22px', padding: '26px', border: `1px solid ${c.line}`, boxShadow: '0 24px 50px -28px rgba(60,50,20,0.35)' }">
    <div class="flex items-center justify-between mb-4">
      <h3 :style="{ margin: 0, fontFamily: dt.font, fontWeight: 900, fontSize: '22px', color: c.ink }">{{ t('donateModal.support', { name: siteConfig.beneficiary.name }) }}</h3>
      <FrIcon name="heart" :size="24" :color="c.primary" />
    </div>

    <!-- Freq toggle -->
    <div :style="{ display: 'flex', background: c.surfaceAlt, borderRadius: '12px', padding: '4px', gap: '4px', border: `1px solid ${c.line}` }">
      <button v-for="o in opts" :key="o.k"
        :style="{
          flex: 1, padding: '10px 8px', border: 'none', cursor: 'pointer', borderRadius: '9px',
          fontFamily: dt.font, fontWeight: 800, fontSize: '14.5px',
          background: freq === o.k ? c.primary : 'transparent',
          color: freq === o.k ? c.primaryInk : c.inkSoft,
          display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '6px', transition: 'all 0.15s',
        }"
        @click="freq = o.k"
      >
        <FrIcon v-if="o.k === 'monthly'" name="repeat" :size="15" :color="freq === o.k ? c.primaryInk : c.inkSoft" />
        {{ o.l }}
      </button>
    </div>

    <div v-if="freq === 'monthly'" :style="{ marginTop: '9px', fontSize: '13px', color: c.primaryDeep, fontWeight: 700, display: 'flex', gap: '6px', alignItems: 'center', lineHeight: 1.4 }">
      <FrIcon name="heart" :size="14" :color="c.primary" /> {{ t('d.donateCardHint') }}
    </div>

    <div style="height: 16px;" />

    <!-- Amount chips -->
    <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: '8px' }">
      <button v-for="a in data.amounts" :key="a"
        :style="{
          padding: '14px 4px', borderRadius: '12px', cursor: 'pointer', fontFamily: dt.font,
          border: `1.5px solid ${amount === a && !custom ? c.primary : c.line}`,
          background: amount === a && !custom ? c.primarySoft : c.surface,
          color: amount === a && !custom ? c.primaryDeep : c.ink,
          fontWeight: 800, fontSize: '17px', transition: 'all 0.12s',
        }"
        @click="amount = a; custom = ''"
      >{{ a }}<span :style="{ fontSize: '12px', opacity: 0.7 }"> zł</span></button>
    </div>
    <div class="mt-2 relative">
      <input
        :value="custom"
        inputmode="numeric"
        :placeholder="t('donate.custom')"
        :style="{
          width: '100%', boxSizing: 'border-box', padding: '13px 48px 13px 14px',
          fontFamily: dt.font, fontSize: '16px', fontWeight: 800, color: c.ink, background: c.surface,
          border: `1.5px solid ${custom ? c.primary : c.line}`, borderRadius: '12px', outline: 'none',
        }"
        @input="custom = ($event.target as HTMLInputElement).value.replace(/[^0-9]/g, '')"
      />
      <span :style="{ position: 'absolute', right: '14px', top: '50%', transform: 'translateY(-50%)', color: c.inkSoft, fontWeight: 800, fontSize: '14px' }">
        zł{{ freq === 'monthly' ? '/mc' : '' }}
      </span>
    </div>

    <div style="height: 18px;" />
    <DBtn variant="primary" :full="true" size="lg" @click="emit('donate', { amount: amt(), freq })">
      <FrIcon name="blik" :size="20" :color="c.primaryInk" />
      {{ t('donateModal.payNow', { amount: zl(amt() || 0) + (freq === 'monthly' ? '/mc' : '') }) }}
    </DBtn>
    <div :style="{ marginTop: '12px', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '7px', fontSize: '12.5px', color: c.inkSoft }">
      <FrIcon name="shield" :size="15" :color="c.inkSoft" /> {{ t('donateModal.securityNote', { foundation: fo.name, krs: fo.krs }) }}
    </div>
  </div>
</template>
