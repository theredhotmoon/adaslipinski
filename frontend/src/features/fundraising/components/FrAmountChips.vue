<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { theme, data } from '../data'
const { c, r } = theme
const { t } = useI18n()

defineProps<{
  modelValue: number
  custom: string
  freq: 'once' | 'monthly'
}>()

const emit = defineEmits<{
  'update:modelValue': [number]
  'update:custom': [string]
}>()
</script>

<template>
  <div>
    <div :style="{ display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: '8px' }">
      <button
        v-for="a in data.amounts"
        :key="a"
        :style="{
          padding: '14px 4px', borderRadius: (r * 0.8) + 'px', cursor: 'pointer', fontFamily: 'inherit',
          border: `1.5px solid ${modelValue === a && !custom ? c.primary : c.line}`,
          background: modelValue === a && !custom ? c.primarySoft : c.surface,
          color: modelValue === a && !custom ? c.primary : c.ink,
          fontWeight: 800, fontSize: '16px', transition: 'all 0.12s',
        }"
        @click="emit('update:modelValue', a); emit('update:custom', '')"
      >
        {{ a }}<span :style="{ fontSize: '11px', fontWeight: 700, opacity: 0.7 }"> zł</span>
      </button>
    </div>
    <div :style="{ marginTop: '8px', position: 'relative' }">
      <input
        :value="custom"
        inputmode="numeric"
        :placeholder="t('donate.custom')"
        :style="{
          width: '100%', boxSizing: 'border-box', padding: '13px 44px 13px 14px', fontFamily: 'inherit',
          fontSize: '15.5px', fontWeight: 700, color: c.ink, background: c.surface,
          border: `1.5px solid ${custom ? c.primary : c.line}`, borderRadius: (r * 0.8) + 'px', outline: 'none',
        }"
        @input="emit('update:custom', ($event.target as HTMLInputElement).value.replace(/[^0-9]/g, ''))"
      />
      <span :style="{ position: 'absolute', right: '14px', top: '50%', transform: 'translateY(-50%)', color: c.inkSoft, fontWeight: 700, fontSize: '14px' }">
        zł{{ freq === 'monthly' ? '/mc' : '' }}
      </span>
    </div>
  </div>
</template>
