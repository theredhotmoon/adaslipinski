<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/features/auth/store'

defineOptions({ inheritAttrs: false })

const props = defineProps<{ value: number; min?: number; max?: number }>()
const emit = defineEmits<{ save: [number] }>()

const auth = useAuthStore()
const editing = ref(false)
const draft = ref(props.value)

function startEdit() { draft.value = props.value; editing.value = true }
function commit() {
  const v = Number(draft.value)
  if (!isNaN(v) && v !== props.value) emit('save', v)
  editing.value = false
}
function cancel() { editing.value = false }
</script>

<template>
  <span v-bind="$attrs">
    <input
      v-if="auth.isAuthenticated && editing"
      v-model.number="draft"
      type="number"
      :min="min"
      :max="max"
      class="w-24 bg-emerald-50 border border-emerald-400 rounded px-1 text-right font-[inherit] text-[inherit] outline-none"
      @blur="commit"
      @keydown.enter.prevent="commit"
      @keydown.escape.prevent="cancel"
      ref="inputEl"
    />
    <span
      v-else
      :class="auth.isAuthenticated && 'cursor-text border-b border-dashed border-emerald-400/50'"
      @click="auth.isAuthenticated && startEdit()"
    ><slot>{{ value }}</slot></span>
  </span>
</template>
