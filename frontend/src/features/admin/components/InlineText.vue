<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { useAuthStore } from '@/features/auth/store'

defineOptions({ inheritAttrs: false })

const props = defineProps<{
  value: string
  tag?: string
  multiline?: boolean
}>()

const emit = defineEmits<{ save: [string] }>()

const auth = useAuthStore()
const el = ref<HTMLElement | null>(null)
let original = ''

onMounted(() => {
  if (el.value) el.value.innerText = props.value
})

watch(() => props.value, (v) => {
  if (el.value && document.activeElement !== el.value) {
    el.value.innerText = v
  }
})

function onFocus() {
  original = el.value?.innerText ?? props.value
}

function onBlur() {
  const curr = (el.value?.innerText ?? '').trim()
  if (!curr) {
    if (el.value) el.value.innerText = original
    return
  }
  if (curr !== original) emit('save', curr)
}

function onKeydown(e: KeyboardEvent) {
  if (!props.multiline && e.key === 'Enter') {
    e.preventDefault()
    el.value?.blur()
  }
  if (e.key === 'Escape') {
    if (el.value) el.value.innerText = original
    el.value?.blur()
  }
}

function onPaste(e: ClipboardEvent) {
  e.preventDefault()
  const text = e.clipboardData?.getData('text/plain') ?? ''
  document.execCommand('insertText', false, text)
}
</script>

<template>
  <component
    :is="tag || 'span'"
    ref="el"
    v-bind="$attrs"
    :contenteditable="auth.isAuthenticated ? 'true' : 'false'"
    :class="[
      $attrs.class,
      auth.isAuthenticated && 'outline-none cursor-text border-b border-dashed border-emerald-400/50 focus:border-emerald-500 focus:bg-emerald-50/20 transition-colors',
    ]"
    spellcheck="false"
    @focus="onFocus"
    @blur="onBlur"
    @keydown="onKeydown"
    @paste="onPaste"
  />
</template>
