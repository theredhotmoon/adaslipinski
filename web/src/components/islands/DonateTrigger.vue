<script setup lang="ts">
// A donate button. Writes the intent to the nanostore; the single <DonateModal>
// island (anywhere on the page) reacts and opens. Pure props in, no globals.
import { openDonate } from '../../lib/donateStore'

const props = withDefaults(
  defineProps<{
    label: string
    amount?: number
    freq?: 'once' | 'monthly'
    item?: string
    variant?: 'primary' | 'bar' | 'soft'
  }>(),
  { variant: 'primary', freq: 'monthly' },
)

function click() {
  openDonate({ amount: props.amount, freq: props.freq, item: props.item })
}
</script>

<template>
  <button type="button" :class="['donate-btn', `is-${variant}`]" @click="click">
    {{ label }}
  </button>
</template>

<style scoped>
.donate-btn {
  border: none;
  border-radius: 16px;
  font-weight: 800;
  font-size: 15px;
  padding: 13px 20px;
  transition: transform 0.12s ease, filter 0.12s ease;
}
.donate-btn:active { transform: scale(0.98); }
.is-primary { background: var(--color-fr-primary); color: var(--color-fr-primary-ink); }
.is-primary:hover { filter: brightness(1.05); }
.is-bar { background: var(--color-fr-primary); color: var(--color-fr-primary-ink); width: 100%; padding: 15px 20px; font-size: 16px; border-radius: 18px; }
.is-soft { background: var(--color-fr-primary-soft); color: var(--color-fr-primary); }
</style>
