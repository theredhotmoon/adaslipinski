<script setup lang="ts">
import { ref } from 'vue'

// Self-contained accordion. Takes resolved FAQ items as props (no CMS fetch, no
// vue-i18n). The question text is also rendered server-side in the .astro shell as
// a <noscript>/SSR-visible heading so the content is indexable even before hydration.
defineProps<{ items: { id: number; q: string; a: string }[] }>()

const open = ref<number | null>(null)
const toggle = (i: number) => { open.value = open.value === i ? null : i }
</script>

<template>
  <div class="faq">
    <div v-for="(it, i) in items" :key="it.id" class="faq-row">
      <button
        type="button"
        class="faq-q"
        :aria-expanded="open === i"
        @click="toggle(i)"
      >
        <span>{{ it.q }}</span>
        <svg
          class="chev"
          :class="{ rot: open === i }"
          width="18" height="18" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
          aria-hidden="true"
        >
          <polyline points="6 9 12 15 18 9" />
        </svg>
      </button>
      <div v-show="open === i" class="faq-a">{{ it.a }}</div>
    </div>
  </div>
</template>

<style scoped>
.faq { display: flex; flex-direction: column; gap: 8px; }
.faq-row {
  background: var(--color-fr-surface);
  border: 1px solid var(--color-fr-line);
  border-radius: 18px;
  overflow: hidden;
}
.faq-q {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px;
  background: transparent;
  border: none;
  text-align: left;
  font-weight: 700;
  font-size: 14.5px;
  color: var(--color-fr-ink);
}
.faq-q > span { flex: 1; }
.chev { flex-shrink: 0; color: var(--color-fr-ink-soft); transition: transform 0.2s; }
.chev.rot { transform: rotate(180deg); }
.faq-a {
  padding: 0 15px 15px;
  color: var(--color-fr-ink-soft);
  font-size: 14px;
  line-height: 1.55;
}
</style>
