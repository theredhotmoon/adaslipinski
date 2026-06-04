<script setup lang="ts">
import { ref } from 'vue'
import FrIcon from './FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import { useAuthStore } from '@/features/auth/store'
import { theme } from '../data'
const { c, r } = theme
const auth = useAuthStore()

defineProps<{ items: { id?: number; q: string; a: string }[] }>()
const emit = defineEmits<{
  editQuestion: [{ id: number; value: string }]
  editAnswer: [{ id: number; value: string }]
  remove: [number]
}>()

const open = ref<number | null>(null)
const toggle = (i: number) => { open.value = open.value === i ? null : i }
</script>

<template>
  <div class="flex flex-col gap-2">
    <div
      v-for="(it, i) in items"
      :key="it.id ?? i"
      :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: r + 'px', overflow: 'hidden' }"
    >
      <div
        :style="{ width: '100%', display: 'flex', alignItems: 'center', gap: '10px', padding: '14px 15px' }"
      >
        <InlineText
          v-if="auth.isAuthenticated && it.id"
          :value="it.q"
          @save="emit('editQuestion', { id: it.id!, value: $event })"
          :style="{ flex: 1, fontWeight: 700, color: c.ink, fontSize: '14.5px' }"
        />
        <button
          v-else
          :style="{ flex: 1, display: 'flex', alignItems: 'center', gap: '10px', background: 'transparent', border: 'none', cursor: 'pointer', textAlign: 'left', padding: 0, fontFamily: 'inherit' }"
          @click="toggle(i)"
        >
          <span :style="{ flex: 1, fontWeight: 700, color: c.ink, fontSize: '14.5px' }">{{ it.q }}</span>
        </button>
        <AdminDelete v-if="it.id" label="pytanie" @click="emit('remove', it.id)" />
        <button
          :style="{ background: 'transparent', border: 'none', cursor: 'pointer', transform: open === i ? 'rotate(180deg)' : 'none', transition: 'transform 0.2s', flexShrink: 0 }"
          @click="toggle(i)"
        >
          <FrIcon name="chevD" :size="18" :color="c.inkSoft" />
        </button>
      </div>
      <div v-if="open === i" :style="{ padding: '0 15px 15px', color: c.inkSoft, fontSize: '14px', lineHeight: 1.55 }">
        <InlineText
          v-if="auth.isAuthenticated && it.id"
          :value="it.a"
          :multiline="true"
          @save="emit('editAnswer', { id: it.id!, value: $event })"
          :style="{ display: 'block' }"
        />
        <template v-else>{{ it.a }}</template>
      </div>
    </div>
  </div>
</template>
