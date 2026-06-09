<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { ImagePlus, Loader2 } from 'lucide-vue-next'
import { useAuthStore } from '@/features/auth/store'
import { useUploadMedia } from '@/features/admin/useCmsApi'

const props = defineProps<{ label?: string; alt?: string }>()
const emit = defineEmits<{ uploaded: [{ id: number; url: string }] }>()

const auth = useAuthStore()
const { t } = useI18n()
const { mutate: upload, isPending } = useUploadMedia()
const input = ref<HTMLInputElement | null>(null)

function pick() {
  input.value?.click()
}

function onChange(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (!file) return
  upload(
    { file, altText: props.alt },
    { onSuccess: (data) => emit('uploaded', data) },
  )
  ;(e.target as HTMLInputElement).value = ''
}
</script>

<template>
  <div v-if="auth.isAuthenticated">
    <input ref="input" type="file" accept="image/*" class="hidden" @change="onChange" />
    <button
      type="button"
      :disabled="isPending"
      :style="{
        display: 'inline-flex', alignItems: 'center', gap: '6px',
        padding: '7px 12px', border: 'none', borderRadius: '999px', cursor: 'pointer',
        background: 'rgba(15,15,20,0.72)', color: '#fff', backdropFilter: 'blur(4px)',
        fontFamily: 'inherit', fontWeight: 700, fontSize: '12.5px', opacity: isPending ? 0.7 : 1,
      }"
      @click.stop="pick"
    >
      <Loader2 v-if="isPending" :size="14" class="animate-spin" />
      <ImagePlus v-else :size="14" />
      {{ isPending ? t('admin.uploading') : (label ?? t('admin.uploadImage')) }}
    </button>
  </div>
</template>
