<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { siteConfig, type LocaleCode } from '@/config/site'
import { setLocale } from '@/i18n'
import { dt } from '../desktopTheme'

const { locale, t } = useI18n()
const { c } = dt

function select(code: LocaleCode) {
  if (locale.value !== code) setLocale(code)
}
</script>

<template>
  <div
    :aria-label="t('lang.switch')"
    :style="{
      display: 'inline-flex', gap: '2px', padding: '3px', borderRadius: '999px',
      background: c.surfaceAlt, border: `1px solid ${c.line}`,
    }"
  >
    <button
      v-for="code in siteConfig.availableLocales"
      :key="code"
      type="button"
      :aria-pressed="locale === code"
      :style="{
        border: 'none', cursor: 'pointer', padding: '6px 12px', borderRadius: '999px',
        fontSize: '13px', fontWeight: 800, textTransform: 'uppercase', fontFamily: dt.font,
        background: locale === code ? c.primary : 'transparent',
        color: locale === code ? c.primaryInk : c.inkSoft,
      }"
      @click="select(code)"
    >
      {{ code }}
    </button>
  </div>
</template>
