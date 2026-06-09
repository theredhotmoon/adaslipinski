<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import DPh from './DPh.vue'
import DPill from './DPill.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import AdminImageUpload from '@/features/admin/components/AdminImageUpload.vue'
import { useUpdateProgress, useCreateProgress, useDeleteProgress } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData, zl } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()

const props = defineProps<{ cols?: number; limit?: number }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const all = computed(() => siteData?.value?.progress ?? staticData.progress as any)
const items = computed(() => props.limit ? all.value.slice(0, props.limit) : all.value)

const { mutate: updatePost } = useUpdateProgress()
const { mutate: createPost, isPending: creating } = useCreateProgress()
const { mutate: deletePost } = useDeleteProgress()

const showAdd = ref(false)
const newPost = ref({ tag: '', title: '', body: '', published_at: '', amount_pln: '' })
function submitPost() {
  const payload: Record<string, unknown> = {
    tag: newPost.value.tag,
    title: newPost.value.title,
    body: newPost.value.body,
    published_at: newPost.value.published_at || new Date().toISOString().slice(0, 10),
  }
  if (newPost.value.amount_pln) payload.amount_pln = parseInt(newPost.value.amount_pln)
  createPost(payload, {
    onSuccess: () => { showAdd.value = false; newPost.value = { tag: '', title: '', body: '', published_at: '', amount_pln: '' } }
  })
}
</script>
<template>
  <div>
    <div :style="{ display: 'grid', gridTemplateColumns: `repeat(${cols ?? 3}, 1fr)`, gap: '18px' }">
      <article v-for="p in items" :key="p.id" :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '18px', overflow: 'hidden', display: 'flex', flexDirection: 'column' }">
        <div style="position: relative;">
          <DPh :label="p.img" :src="p.imgUrl" ratio="16/10" :radius="0" />
          <div style="position: absolute; bottom: 10px; right: 10px;">
            <AdminImageUpload :alt="p.title" @uploaded="updatePost({ id: p.id, image_id: $event.id })" />
          </div>
        </div>
        <div class="p-[18px]">
          <div class="flex items-center gap-2.5">
            <DPill tone="soft" style="font-size: 11.5px; padding: 4px 10px;">
              <InlineText :value="p.tag ?? ''" @save="updatePost({ id: p.id, tag: $event })" />
            </DPill>
            <span :style="{ fontSize: '13px', color: c.inkSoft, fontWeight: 600 }">{{ p.date }}</span>
            <span v-if="p.amount" :style="{ marginLeft: 'auto', fontSize: '13px', fontWeight: 800, color: c.primaryDeep }">{{ zl(p.amount) }}</span>
            <AdminDelete :label="t('admin.del.post')" :style="{ marginLeft: p.amount ? '8px' : 'auto' }" @click="deletePost(p.id)" />
          </div>
          <InlineText tag="h3" :value="p.title" @save="updatePost({ id: p.id, title: $event })" :style="{ margin: '11px 0 0', fontFamily: dt.font, fontWeight: 800, fontSize: '18.5px', color: c.ink, lineHeight: 1.25, display: 'block' }" />
          <InlineText tag="p" :value="p.body" :multiline="true" @save="updatePost({ id: p.id, body: $event })" :style="{ margin: '8px 0 0', color: c.inkSoft, fontSize: '14.5px', lineHeight: 1.55, display: 'block' }" />
        </div>
      </article>
    </div>
    <div class="max-w-[300px] mt-4">
      <AdminAdd :label="t('progress.addPost')" @click="showAdd = true" />
    </div>

    <AdminFormModal :title="t('progress.addPostTitle')" :open="showAdd" :saving="creating" @close="showAdd = false" @save="submitPost">
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('progress.tagLabel') }}</span>
        <input v-model="newPost.tag" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('progress.titleLabel') }}</span>
        <input v-model="newPost.title" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('progress.bodyLabel') }}</span>
        <textarea v-model="newPost.body" rows="4" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400 resize-none" />
      </label>
      <div class="grid grid-cols-2 gap-3">
        <label class="block">
          <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('progress.dateLabel') }}</span>
          <input v-model="newPost.published_at" type="date" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
        </label>
        <label class="block">
          <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('progress.amountLabel') }}</span>
          <input v-model="newPost.amount_pln" type="number" min="0" :placeholder="t('common.optional')" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
        </label>
      </div>
    </AdminFormModal>
  </div>
</template>
