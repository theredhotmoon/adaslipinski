<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useStore } from '@nanostores/vue'
import { donate, closeDonate } from '../../lib/donateStore'
import { zl } from '../../i18n/config'

// The one donate modal for the whole page. It owns no content — everything (amounts,
// foundation details, and every label) arrives as serializable props resolved
// server-side, and it reacts to the shared nanostore that the triggers write to.
export interface DonateStrings {
  support: string          // template with {name}
  fund: string             // template with {item}
  once: string
  monthly: string
  monthlyHint: string
  method: string
  methodCard: string
  methodTransfer: string
  blikPrompt: string
  blikNote: string
  accountPln: string
  transferTitleReq: string
  didTransfer: string
  payNow: string           // template with {amount}
  payNowMonthly: string    // template with {amount}
  securityNote: string     // already interpolated (foundation, krs)
  thanks: string
  thanksMonthly: string    // template with {amount},{name}
  thanksOnce: string       // template with {amount},{name}
  close: string
  custom: string
  copy: string
  copied: string
}

const props = defineProps<{
  strings: DonateStrings
  amounts: number[]
  name: string
  accountPln: string
  cel: string
}>()

const state = useStore(donate)

const freq = ref<'once' | 'monthly'>('monthly')
const amount = ref(100)
const custom = ref('')
const method = ref<'blik' | 'card' | 'transfer'>('blik')
const blik = ref('')
const step = ref<'form' | 'thanks'>('form')
const copied = ref(false)

// Re-seed the form each time the store flips to open.
watch(() => state.value.open, (isOpen) => {
  if (!isOpen) return
  freq.value = state.value.freq
  amount.value = state.value.amount
  custom.value = ''
  method.value = 'blik'
  blik.value = ''
  step.value = 'form'
})

const finalAmount = computed(() => (custom.value ? parseInt(custom.value, 10) || 0 : amount.value))

function fmt(tpl: string, vars: Record<string, string | number>) {
  return tpl.replace(/\{(\w+)\}/g, (_, k) => (k in vars ? String(vars[k]) : `{${k}}`))
}

const title = computed(() =>
  state.value.item ? fmt(props.strings.fund, { item: state.value.item }) : fmt(props.strings.support, { name: props.name }),
)
const payLabel = computed(() => {
  if (method.value === 'transfer') return props.strings.didTransfer
  const tpl = freq.value === 'monthly' ? props.strings.payNowMonthly : props.strings.payNow
  return fmt(tpl, { amount: zl(finalAmount.value) })
})
const thanksMessage = computed(() =>
  fmt(freq.value === 'monthly' ? props.strings.thanksMonthly : props.strings.thanksOnce, {
    amount: zl(finalAmount.value),
    name: props.name,
  }),
)

function copyCel() {
  try { navigator.clipboard?.writeText(props.cel) } catch { /* clipboard blocked */ }
  copied.value = true
  setTimeout(() => { copied.value = false }, 1500)
}
</script>

<template>
  <Teleport to="body">
    <div v-if="state.open" class="overlay" role="dialog" aria-modal="true">
      <div class="scrim" @click="closeDonate" />
      <div class="sheet animate-sheet-up">
        <div class="grip-wrap"><div class="grip" /></div>

        <!-- Thanks -->
        <div v-if="step === 'thanks'" class="thanks">
          <div class="thanks-badge animate-pop">💛</div>
          <h3 class="thanks-title">{{ strings.thanks }}</h3>
          <p class="thanks-body">{{ thanksMessage }}</p>
          <button class="btn ghost" @click="closeDonate">{{ strings.close }}</button>
        </div>

        <!-- Form -->
        <div v-else class="form">
          <div class="form-head">
            <h3 class="form-title">{{ title }}</h3>
            <button class="x" :aria-label="strings.close" @click="closeDonate">✕</button>
          </div>

          <!-- Frequency -->
          <div class="seg">
            <button :class="['seg-btn', { on: freq === 'once' }]" @click="freq = 'once'">{{ strings.once }}</button>
            <button :class="['seg-btn', { on: freq === 'monthly' }]" @click="freq = 'monthly'">↻ {{ strings.monthly }}</button>
          </div>
          <p v-if="freq === 'monthly'" class="hint">💛 {{ strings.monthlyHint }}</p>

          <!-- Amounts -->
          <div class="chips">
            <button
              v-for="a in amounts"
              :key="a"
              :class="['chip', { on: amount === a && !custom }]"
              @click="amount = a; custom = ''"
            >{{ a }} zł</button>
          </div>
          <input
            v-model="custom"
            class="custom"
            inputmode="numeric"
            :placeholder="strings.custom"
            @input="custom = custom.replace(/[^0-9]/g, '')"
          />

          <!-- Method -->
          <div class="label">{{ strings.method }}</div>
          <div class="methods">
            <button :class="['m', { on: method === 'blik' }]" @click="method = 'blik'">BLIK</button>
            <button :class="['m', { on: method === 'card' }]" @click="method = 'card'">{{ strings.methodCard }}</button>
            <button :class="['m', { on: method === 'transfer' }]" @click="method = 'transfer'">{{ strings.methodTransfer }}</button>
          </div>

          <div v-if="method === 'blik'" class="blik-wrap">
            <div class="sub">{{ strings.blikPrompt }}</div>
            <input
              v-model="blik"
              class="blik"
              inputmode="numeric"
              maxlength="6"
              placeholder="• • • • • •"
              @input="blik = blik.replace(/[^0-9]/g, '').slice(0, 6)"
            />
            <div class="note">{{ strings.blikNote }}</div>
          </div>

          <div v-else-if="method === 'transfer'" class="transfer">
            <div class="copy-label">{{ strings.accountPln }}</div>
            <div class="copy-field mono">{{ accountPln }}</div>
            <div class="copy-label">{{ strings.transferTitleReq }}</div>
            <button class="copy-field" @click="copyCel">
              <span>{{ cel }}</span>
              <span class="copy-cta">{{ copied ? strings.copied : strings.copy }}</span>
            </button>
          </div>

          <button class="btn primary" @click="step = 'thanks'">{{ payLabel }}</button>
          <p class="security">🛡 {{ strings.securityNote }}</p>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
.overlay { position: fixed; inset: 0; z-index: 2000; display: flex; flex-direction: column; justify-content: flex-end; }
.scrim { position: absolute; inset: 0; background: rgba(20, 20, 25, 0.45); backdrop-filter: blur(2px); }
.sheet {
  position: relative; background: var(--color-fr-bg);
  border-radius: 28px 28px 0 0; width: 100%; max-width: 460px; margin: 0 auto;
  max-height: 92%; overflow-y: auto; box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.25);
}
.grip-wrap { display: flex; justify-content: center; padding-top: 10px; }
.grip { width: 40px; height: 5px; border-radius: 9px; background: var(--color-fr-line); }

.thanks { padding: 16px 22px 28px; text-align: center; }
.thanks-badge { width: 76px; height: 76px; border-radius: 999px; background: var(--color-fr-primary-soft); display: grid; place-items: center; margin: 8px auto 16px; font-size: 34px; }
.thanks-title { margin: 0; font-size: 24px; font-weight: 800; color: var(--color-fr-ink); }
.thanks-body { margin: 8px 0 18px; color: var(--color-fr-ink-soft); font-size: 14.5px; line-height: 1.5; }

.form { padding: 14px 18px 22px; }
.form-head { display: flex; align-items: center; margin-bottom: 14px; }
.form-title { flex: 1; margin: 0; font-size: 22px; font-weight: 800; color: var(--color-fr-ink); }
.x { border: none; background: var(--color-fr-surface-alt); border-radius: 999px; width: 34px; height: 34px; color: var(--color-fr-ink-soft); }

.seg { display: flex; gap: 4px; padding: 4px; background: var(--color-fr-surface-alt); border: 1px solid var(--color-fr-line); border-radius: 22px; }
.seg-btn { flex: 1; padding: 12px 8px; border: none; background: transparent; border-radius: 16px; font-weight: 700; font-size: 14px; color: var(--color-fr-ink-soft); }
.seg-btn.on { background: var(--color-fr-primary); color: var(--color-fr-primary-ink); }
.hint { margin: 8px 0 0; font-size: 12.5px; color: var(--color-fr-primary); font-weight: 700; }

.chips { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 16px; }
.chip { padding: 14px 4px; border: 1.5px solid var(--color-fr-line); background: var(--color-fr-surface); border-radius: 16px; font-weight: 800; font-size: 15px; color: var(--color-fr-ink); }
.chip.on { border-color: var(--color-fr-primary); background: var(--color-fr-primary-soft); color: var(--color-fr-primary); }
.custom { width: 100%; margin-top: 8px; padding: 13px 14px; border: 1.5px solid var(--color-fr-line); border-radius: 16px; font-size: 15.5px; font-weight: 700; color: var(--color-fr-ink); background: var(--color-fr-surface); outline: none; }

.label { font-size: 12.5px; font-weight: 700; color: var(--color-fr-ink-soft); margin: 18px 0 8px; }
.methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.m { padding: 12px 4px; border: 1.5px solid var(--color-fr-line); background: var(--color-fr-surface); border-radius: 14px; font-weight: 700; font-size: 13.5px; color: var(--color-fr-ink); }
.m.on { border-color: var(--color-fr-primary); background: var(--color-fr-primary-soft); color: var(--color-fr-primary); }

.blik-wrap { margin-top: 14px; }
.sub { font-size: 13px; color: var(--color-fr-ink-soft); margin-bottom: 8px; }
.blik { width: 100%; text-align: center; letter-spacing: 0.4em; padding: 16px; font-size: 24px; font-weight: 800; border: 1.5px solid var(--color-fr-line); border-radius: 16px; background: var(--color-fr-surface); color: var(--color-fr-ink); outline: none; }
.note { margin-top: 8px; font-size: 12px; color: var(--color-fr-ink-soft); text-align: center; }

.transfer { margin-top: 14px; }
.copy-label { font-size: 12px; color: var(--color-fr-ink-soft); margin: 10px 0 4px; font-weight: 600; }
.copy-field { width: 100%; display: flex; align-items: center; gap: 10px; padding: 11px 12px; background: var(--color-fr-surface-alt); border: 1px solid var(--color-fr-line); border-radius: 14px; text-align: left; color: var(--color-fr-ink); font-weight: 600; }
.copy-field > span:first-child { flex: 1; word-break: break-all; }
.copy-field.mono { font-family: ui-monospace, Menlo, monospace; font-size: 14px; }
.copy-cta { flex-shrink: 0; font-size: 12.5px; font-weight: 700; color: var(--color-fr-primary); }

.btn { width: 100%; border: none; border-radius: 18px; font-weight: 800; font-size: 16px; padding: 15px; margin-top: 18px; }
.btn.primary { background: var(--color-fr-primary); color: var(--color-fr-primary-ink); }
.btn.ghost { background: var(--color-fr-surface-alt); color: var(--color-fr-ink); }
.security { margin: 10px 0 0; text-align: center; font-size: 11.5px; color: var(--color-fr-ink-soft); }
</style>
