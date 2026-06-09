<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import DBtn from './DBtn.vue'
import DCopyChip from './DCopyChip.vue'
import { siteConfig } from '@/config/site'
import { dt } from '../desktopTheme'
import { data, zl } from '../../data'
const { c } = dt
const { t } = useI18n()
const name = siteConfig.beneficiary.name
const fo = siteConfig.foundation

const props = defineProps<{
  open: boolean
  amount?: number
  freq?: 'once' | 'monthly'
  item?: string | null
}>()
const emit = defineEmits<{ close: [] }>()

const method = ref<'blik' | 'card' | 'transfer'>('blik')
const blik = ref('')
const step = ref<'form' | 'thanks'>('form')

watch(() => props.open, (v) => {
  if (v) { method.value = 'blik'; blik.value = ''; step.value = 'form' }
})

const methods = computed(() => [
  { k: 'blik' as const, l: 'BLIK', icon: 'blik' },
  { k: 'card' as const, l: t('d.modal.methodCardApple'), icon: 'card' },
  { k: 'transfer' as const, l: t('donateModal.methodTransfer'), icon: 'transfer' },
])

const freqWord = computed(() => props.freq === 'monthly' ? t('d.modal.freqMonthly') : t('d.modal.freqOnce'))
const amountWithFreq = computed(() => zl(props.amount ?? 100) + (props.freq === 'monthly' ? ' ' + t('d.modal.freqMonthly') : ''))
const thanksMessage = computed(() => t('d.modal.thanks', { amount: amountWithFreq.value, name }))
const payLabel = computed(() =>
  method.value === 'transfer'
    ? t('donateModal.didTransfer')
    : t('donateModal.payNow', { amount: amountWithFreq.value }),
)
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="fixed inset-0 z-[3000] flex items-center justify-center p-6" :style="{ fontFamily: dt.font }">
      <div class="absolute inset-0" :style="{ background: 'rgba(35,30,12,0.5)', backdropFilter: 'blur(3px)' }" @click="emit('close')" />
      <div :style="{ position: 'relative', background: c.bg, borderRadius: '24px', width: '100%', maxWidth: '460px', maxHeight: '88vh', overflowY: 'auto', boxShadow: '0 40px 90px rgba(0,0,0,0.3)' }" class="animate-pop">

        <!-- Thanks step -->
        <div v-if="step === 'thanks'" class="p-8 text-center">
          <div :style="{ width: '80px', height: '80px', borderRadius: '999px', background: c.primarySoft, display: 'grid', placeItems: 'center', margin: '0 auto 18px' }">
            <FrIcon name="heart" :size="42" :color="c.primary" />
          </div>
          <h3 :style="{ margin: 0, fontWeight: 900, fontSize: '26px', color: c.ink }">{{ t('donateModal.thanks') }}</h3>
          <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15.5px', lineHeight: 1.55 }">{{ thanksMessage }}</p>
          <div class="flex gap-2.5 mt-5">
            <DBtn variant="soft" :full="true">
              <FrIcon name="share" :size="17" :color="c.primaryDeep" /> {{ t('donateModal.share') }}
            </DBtn>
            <DBtn variant="ghost" :full="true" @click="emit('close')">{{ t('common.close') }}</DBtn>
          </div>
        </div>

        <!-- Form step -->
        <div v-else class="p-7 pb-8">
          <div class="flex items-start justify-between mb-[18px]">
            <div>
              <h3 :style="{ margin: 0, fontWeight: 900, fontSize: '24px', color: c.ink }">{{ item ? t('donateModal.fund', { item }) : t('donateModal.support', { name }) }}</h3>
              <div :style="{ marginTop: '4px', fontSize: '15px', color: c.inkSoft, fontWeight: 700 }">{{ zl(amount ?? 100) }} {{ freqWord }}</div>
            </div>
            <button :style="{ border: 'none', background: c.surfaceAlt, borderRadius: '999px', width: '38px', height: '38px', cursor: 'pointer', display: 'grid', placeItems: 'center' }" @click="emit('close')">
              <FrIcon name="close" :size="19" :color="c.inkSoft" />
            </button>
          </div>

          <div class="grid grid-cols-3 gap-2">
            <button v-for="m in methods" :key="m.k"
              :style="{
                padding: '12px 4px', borderRadius: '12px', cursor: 'pointer', fontFamily: dt.font,
                border: `1.5px solid ${method === m.k ? c.primary : c.line}`,
                background: method === m.k ? c.primarySoft : c.surface,
                color: method === m.k ? c.primaryDeep : c.ink,
                fontWeight: 800, fontSize: '12.5px', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '6px', lineHeight: 1.2, textAlign: 'center',
              }"
              @click="method = m.k"
            >
              <FrIcon :name="m.icon" :size="22" :color="method === m.k ? c.primary : c.inkSoft" :stroke-width="1.8" />{{ m.l }}
            </button>
          </div>

          <div class="h-[18px]" />

          <!-- BLIK -->
          <div v-if="method === 'blik'">
            <div :style="{ fontSize: '13.5px', color: c.inkSoft, marginBottom: '8px' }">{{ t('donateModal.blikPrompt') }}</div>
            <input v-model="blik" inputmode="numeric" placeholder="• • • • • •" maxlength="6"
              :style="{ width: '100%', boxSizing: 'border-box', textAlign: 'center', letterSpacing: '0.45em', padding: '16px', fontSize: '26px', fontWeight: 800, fontFamily: 'ui-monospace, Menlo, monospace', color: c.ink, background: c.surface, border: `1.5px solid ${c.line}`, borderRadius: '14px', outline: 'none' }"
              @input="blik = blik.replace(/[^0-9]/g, '').slice(0, 6)"
            />
          </div>

          <!-- Card -->
          <div v-else-if="method === 'card'" class="flex flex-col gap-2">
            <input :placeholder="t('donateModal.cardNumber')" :style="{ padding: '13px 14px', fontFamily: 'ui-monospace, Menlo, monospace', fontSize: '15px', fontWeight: 700, color: c.ink, background: c.surface, border: `1.5px solid ${c.line}`, borderRadius: '12px', outline: 'none', width: '100%' }" />
            <div class="flex gap-2">
              <input placeholder="MM / RR" :style="{ flex: 1, padding: '13px 14px', fontFamily: 'ui-monospace, Menlo, monospace', fontSize: '15px', fontWeight: 700, color: c.ink, background: c.surface, border: `1.5px solid ${c.line}`, borderRadius: '12px', outline: 'none' }" />
              <input placeholder="CVC" :style="{ width: '90px', padding: '13px 14px', fontFamily: 'ui-monospace, Menlo, monospace', fontSize: '15px', fontWeight: 700, color: c.inkSoft, background: c.surface, border: `1.5px solid ${c.line}`, borderRadius: '12px', outline: 'none' }" />
            </div>
          </div>

          <!-- Transfer -->
          <div v-else class="flex flex-col gap-2">
            <DCopyChip :value="data.foundation.accounts[0].iban" />
            <DCopyChip :value="`${t('foundation.transferTitle')}: ${data.foundation.cel}`" :mono="false" />
          </div>

          <div class="h-5" />
          <DBtn variant="primary" :full="true" size="lg" @click="step = 'thanks'">
            {{ payLabel }}
          </DBtn>
          <div :style="{ marginTop: '12px', textAlign: 'center', fontSize: '12px', color: c.inkSoft, display: 'flex', gap: '6px', alignItems: 'center', justifyContent: 'center' }">
            <FrIcon name="shield" :size="14" :color="c.inkSoft" /> {{ t('donateModal.securityNote', { foundation: fo.name, krs: fo.krs }) }}
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>
