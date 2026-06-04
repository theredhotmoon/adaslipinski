<script setup lang="ts">
import { ref, watch } from 'vue'
import FrIcon from './FrIcon.vue'
import FrFreqToggle from './FrFreqToggle.vue'
import FrAmountChips from './FrAmountChips.vue'
import FrCopyField from './FrCopyField.vue'
import FrPh from './FrPh.vue'
import FrBtn from './FrBtn.vue'
import { theme, data, zl } from '../data'
const { c, r, f } = theme

const props = defineProps<{
  open: boolean
  initAmount?: number
  initFreq?: 'once' | 'monthly'
  initItem?: string | null
}>()

const emit = defineEmits<{ close: [] }>()

const freq = ref<'once' | 'monthly'>('monthly')
const amount = ref(100)
const custom = ref('')
const method = ref<'blik' | 'card' | 'transfer'>('blik')
const blik = ref('')
const step = ref<'form' | 'thanks'>('form')

watch(() => props.open, (v) => {
  if (v) {
    freq.value = props.initFreq ?? 'monthly'
    amount.value = props.initAmount ?? 100
    custom.value = ''
    method.value = 'blik'
    blik.value = ''
    step.value = 'form'
  }
})

const finalAmount = () => custom.value ? parseInt(custom.value, 10) : amount.value

function cardInputStyle(extra = '') {
  return [
    { padding: '13px 14px', fontFamily: `ui-monospace, Menlo, monospace`, fontSize: '15px', fontWeight: '600',
      color: c.ink, background: c.surface, border: `1.5px solid ${c.line}`,
      borderRadius: (r * 0.8) + 'px', outline: 'none', width: '100%' },
    extra,
  ]
}

const methods = [
  { k: 'blik' as const, l: 'BLIK', icon: 'blik' },
  { k: 'card' as const, l: 'Karta', icon: 'card' },
  { k: 'transfer' as const, l: 'Przelew', icon: 'transfer' },
]
</script>

<template>
  <Teleport to="body">
    <div v-if="open" style="position: fixed; inset: 0; z-index: 2000; display: flex; flex-direction: column; justify-content: flex-end;">
      <div style="position: absolute; inset: 0; background: rgba(20,20,25,0.45); backdrop-filter: blur(2px);" @click="emit('close')" />
      <div
        :style="{
          position: 'relative', background: c.bg, borderTopLeftRadius: '28px', borderTopRightRadius: '28px',
          maxHeight: '92%', width: '100%', maxWidth: '430px', margin: '0 auto',
          display: 'flex', flexDirection: 'column', boxShadow: '0 -10px 40px rgba(0,0,0,0.25)',
        }"
        class="animate-sheet-up"
      >
        <div style="display: flex; justify-content: center; padding-top: 10px;">
          <div :style="{ width: '40px', height: '5px', borderRadius: '9px', background: c.line }" />
        </div>

        <!-- Thanks step -->
        <div v-if="step === 'thanks'" :style="{ padding: '16px 22px 26px', textAlign: 'center' }">
          <div :style="{ width: '76px', height: '76px', borderRadius: '999px', background: c.primarySoft, display: 'grid', placeItems: 'center', margin: '8px auto 16px' }" class="animate-pop">
            <FrIcon name="heart" :size="40" :color="c.primary" />
          </div>
          <h3 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '24px', color: c.ink, letterSpacing: f.hLetter }">Dziękujemy! 💛</h3>
          <p :style="{ margin: '8px 0 0', color: c.inkSoft, fontSize: '14.5px', lineHeight: 1.5 }">
            {{ freq === 'monthly'
              ? `Twoje ${zl(finalAmount())} co miesiąc to ${Math.max(1, Math.round(finalAmount() / 100))} ${finalAmount() >= 100 ? 'tydzień' : 'krok'} rehabilitacji Adasia. Adam i jego rodzice dziękują!`
              : `Twoja wpłata ${zl(finalAmount())} trafia prosto do Adasia. Adam i jego rodzice dziękują!` }}
          </p>
          <div v-if="freq === 'once'" :style="{ marginTop: '18px', padding: '14px', background: c.primarySoft, borderRadius: r + 'px', textAlign: 'left' }">
            <div :style="{ fontWeight: 800, color: c.ink, fontSize: '14.5px', display: 'flex', alignItems: 'center', gap: '7px' }">
              <FrIcon name="repeat" :size="18" :color="c.primary" /> Powtarzać tę kwotę co miesiąc?
            </div>
            <p :style="{ margin: '6px 0 12px', fontSize: '13px', color: c.inkSoft, lineHeight: 1.5 }">Stałe wsparcie pomaga najbardziej — potwierdzasz raz, przerywasz kiedy chcesz.</p>
            <FrBtn variant="primary" :full="true" @click="freq = 'monthly'">Tak, wspieram co miesiąc</FrBtn>
          </div>
          <div :style="{ marginTop: '16px', display: 'flex', gap: '8px' }">
            <FrBtn variant="soft" :full="true">
              <FrIcon name="share" :size="17" :color="c.primary" /> Udostępnij
            </FrBtn>
            <FrBtn variant="ghost" :full="true" @click="emit('close')">Zamknij</FrBtn>
          </div>
        </div>

        <!-- Form step -->
        <div v-else style="overflow-y: auto; padding: 14px 18px 22px;">
          <div :style="{ display: 'flex', alignItems: 'center', marginBottom: '14px' }">
            <h3 :style="{ flex: 1, margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '22px', color: c.ink, letterSpacing: f.hLetter }">
              {{ initItem ? `Sfinansuj: ${initItem}` : 'Wesprzyj Adasia' }}
            </h3>
            <button :style="{ border: 'none', background: c.surfaceAlt, borderRadius: '999px', width: '34px', height: '34px', cursor: 'pointer', display: 'grid', placeItems: 'center' }" @click="emit('close')">
              <FrIcon name="close" :size="18" :color="c.inkSoft" />
            </button>
          </div>

          <FrFreqToggle v-model="freq" size="lg" />
          <div v-if="freq === 'monthly'" :style="{ marginTop: '8px', fontSize: '12.5px', color: c.primary, fontWeight: 700, display: 'flex', alignItems: 'center', gap: '6px' }">
            <FrIcon name="heart" :size="14" :color="c.primary" /> Najbardziej pomaga regularne wsparcie — możesz przerwać kiedy chcesz.
          </div>

          <div style="height: 16px" />
          <FrAmountChips v-model="amount" v-model:custom="custom" :freq="freq" />

          <div style="height: 18px" />
          <div :style="{ fontSize: '12.5px', fontWeight: 700, color: c.inkSoft, marginBottom: '8px' }">Metoda wpłaty</div>
          <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: '8px' }">
            <button
              v-for="m in methods"
              :key="m.k"
              :style="{
                padding: '12px 4px', borderRadius: (r * 0.8) + 'px', cursor: 'pointer', fontFamily: 'inherit',
                border: `1.5px solid ${method === m.k ? c.primary : c.line}`,
                background: method === m.k ? c.primarySoft : c.surface,
                color: method === m.k ? c.primary : c.ink, fontWeight: 700, fontSize: '13.5px',
                display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '5px',
              }"
              @click="method = m.k"
            >
              <FrIcon :name="m.icon" :size="22" :color="method === m.k ? c.primary : c.inkSoft" :stroke-width="1.8" />
              {{ m.l }}
            </button>
          </div>

          <div style="height: 14px" />

          <!-- BLIK -->
          <div v-if="method === 'blik'">
            <div :style="{ fontSize: '13px', color: c.inkSoft, marginBottom: '8px' }">Wpisz 6-cyfrowy kod z aplikacji banku:</div>
            <input
              v-model="blik"
              inputmode="numeric"
              placeholder="• • • • • •"
              maxlength="6"
              :style="{
                width: '100%', boxSizing: 'border-box', textAlign: 'center', letterSpacing: '0.4em',
                padding: '16px', fontSize: '24px', fontWeight: 800, fontFamily: `ui-monospace, Menlo, monospace`,
                color: c.ink, background: c.surface, border: `1.5px solid ${c.line}`, borderRadius: (r * 0.8) + 'px', outline: 'none',
              }"
              @input="blik = (blik as string).replace(/[^0-9]/g, '').slice(0, 6)"
            />
            <div :style="{ marginTop: '8px', fontSize: '12px', color: c.inkSoft, textAlign: 'center' }">Bez prowizji dla zbiórek OPP · potwierdzenie w 2 sekundy</div>
          </div>

          <!-- Card -->
          <div v-else-if="method === 'card'" class="flex flex-col gap-2">
            <input placeholder="Numer karty" :style="cardInputStyle()" />
            <div class="flex gap-2">
              <input placeholder="MM / RR" :style="cardInputStyle('flex:1')" />
              <input placeholder="CVC" :style="cardInputStyle('flex:1')" />
            </div>
            <div class="flex gap-2 mt-0.5">
              <FrBtn variant="ink" :full="true" size="sm"> Pay</FrBtn>
              <FrBtn variant="ghost" :full="true" size="sm">G Pay</FrBtn>
            </div>
          </div>

          <!-- Transfer -->
          <div v-else>
            <FrCopyField label="Numer konta (PLN)" :value="data.foundation.accounts[0].iban" />
            <FrCopyField label="Tytuł przelewu — KONIECZNIE" :value="data.foundation.cel" :mono="false" />
            <div :style="{ display: 'flex', gap: '12px', alignItems: 'center', marginTop: '4px' }">
              <FrPh label="Kod QR przelewu" :h="84" :radius="Math.round(r * 0.6)" style="width: 84px; flex-shrink: 0;" />
              <div :style="{ fontSize: '12px', color: c.inkSoft, lineHeight: 1.5 }">Zeskanuj w aplikacji banku — kwota i tytuł wypełnią się same.</div>
            </div>
          </div>

          <div style="height: 18px" />
          <FrBtn variant="primary" :full="true" size="lg" @click="step = 'thanks'">
            {{ method === 'transfer' ? 'Zrobiłem przelew' : `Wpłać ${zl(finalAmount() || 0)}${freq === 'monthly' ? ' co miesiąc' : ''}` }}
          </FrBtn>
          <div :style="{ marginTop: '10px', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '6px', fontSize: '11.5px', color: c.inkSoft }">
            <FrIcon name="shield" :size="14" :color="c.inkSoft" /> Płatność na subkonto Fundacji „Słoneczko" · KRS {{ data.foundation.krs }}
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

