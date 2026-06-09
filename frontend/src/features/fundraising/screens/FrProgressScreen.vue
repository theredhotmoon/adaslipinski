<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMutation } from '@tanstack/vue-query'
import FrCard from '../components/FrCard.vue'
import FrBtn from '../components/FrBtn.vue'
import FrPill from '../components/FrPill.vue'
import FrPh from '../components/FrPh.vue'
import FrIcon from '../components/FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import AdminImageUpload from '@/features/admin/components/AdminImageUpload.vue'
import { useUpdateProgress, useCreateProgress, useDeleteProgress } from '@/features/admin/useCmsApi'
import { siteConfig } from '@/config/site'
import { theme, data as staticData, zl } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const { t } = useI18n()
const name = siteConfig.beneficiary.name

const emit = defineEmits<{ donate: [{ amount: number; freq: 'once' | 'monthly' }] }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const posts = computed(() => siteData?.value?.progress ?? staticData.progress as any)

const { mutate: updatePost } = useUpdateProgress()
const { mutate: createPost, isPending: creating } = useCreateProgress()
const { mutate: deletePost } = useDeleteProgress()

// Newsletter
const email = ref('')
const subscribed = ref(false)
const { mutate: subscribe, isPending } = useMutation({
  mutationFn: async (e: string) => { await new Promise(r => setTimeout(r, 600)); return e },
  onSuccess: () => { subscribed.value = true },
})

// Add post modal
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
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">{{ t('progress.kicker') }}</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">{{ t('progress.title', { name }) }}</h1>
      <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.5 }">{{ t('progress.subtitle') }}</p>
    </div>

    <div style="padding: 16px 18px 0; display: flex; flex-direction: column; gap: 16px;">
      <FrCard v-for="p in posts" :key="p.id" :pad="0" :style="{ overflow: 'hidden' }">
        <div style="position: relative;">
          <FrPh :label="p.img" :src="p.imgUrl" :h="180" :radius="0" />
          <div style="position: absolute; bottom: 8px; right: 8px;">
            <AdminImageUpload :alt="p.title" @uploaded="updatePost({ id: p.id, image_id: $event.id })" />
          </div>
        </div>
        <div style="padding: 16px;">
          <div :style="{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }">
            <FrPill tone="soft">
              <InlineText :value="p.tag ?? ''" @save="updatePost({ id: p.id, tag: $event })" />
            </FrPill>
            <span :style="{ fontSize: '12px', color: c.inkSoft, fontWeight: 600 }">{{ p.date }}</span>
            <span v-if="p.amount" :style="{ marginLeft: 'auto', fontSize: '12.5px', fontWeight: 800, color: c.primary }">{{ zl(p.amount) }}</span>
            <AdminDelete :label="t('admin.del.post')" @click="deletePost(p.id)" />
          </div>
          <InlineText
            tag="h3"
            :value="p.title"
            @save="updatePost({ id: p.id, title: $event })"
            :style="{ margin: '10px 0 0', fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '18px', color: c.ink, letterSpacing: f.hLetter, lineHeight: 1.25, display: 'block' }"
          />
          <InlineText
            tag="p"
            :value="p.body"
            :multiline="true"
            @save="updatePost({ id: p.id, body: $event })"
            :style="{ margin: '7px 0 0', color: c.inkSoft, fontSize: '14px', lineHeight: 1.55, display: 'block' }"
          />
          <div :style="{ marginTop: '14px', display: 'flex', gap: '8px' }">
            <FrBtn variant="soft" size="sm" :style="{ borderRadius: (r * 0.7) + 'px' }">
              <FrIcon name="share" :size="15" :color="c.primary" /> {{ t('progress.share') }}
            </FrBtn>
            <FrBtn variant="primary" size="sm" :style="{ borderRadius: (r * 0.7) + 'px' }" @click="emit('donate', { amount: 100, freq: 'monthly' })">{{ t('progress.donateMore') }}</FrBtn>
          </div>
        </div>
      </FrCard>

      <AdminAdd :label="t('progress.addPost')" @click="showAdd = true" />
    </div>

    <!-- Newsletter -->
    <div style="padding: 20px 18px 0;">
      <FrCard style="text-align: center;">
        <FrIcon name="mail" :size="28" :color="c.primary" />
        <div :style="{ fontWeight: 800, color: c.ink, fontSize: '15.5px', marginTop: '6px' }">{{ t('progress.newsletterTitle') }}</div>
        <p :style="{ margin: '5px 0 12px', fontSize: '13px', color: c.inkSoft, lineHeight: 1.5 }">{{ t('progress.newsletterSub', { name }) }}</p>
        <div v-if="subscribed" :style="{ color: c.primary, fontWeight: 700, fontSize: '14px', display: 'flex', justifyContent: 'center', alignItems: 'center', gap: '6px' }">
          <FrIcon name="check" :size="18" :color="c.primary" /> {{ t('progress.subscribed') }}
        </div>
        <div v-else class="flex gap-2">
          <input v-model="email" type="email" :placeholder="t('progress.emailPlaceholder')" :style="{ flex: 1, padding: '10px 12px', border: `1px solid ${c.line}`, borderRadius: (r * 0.7) + 'px', fontSize: '14px', fontFamily: 'inherit', color: c.ink, background: c.surfaceAlt, outline: 'none' }" />
          <FrBtn variant="primary" size="sm" :style="{ borderRadius: (r * 0.7) + 'px' }" @click="subscribe(email)">
            {{ isPending ? '…' : t('common.ok') }}
          </FrBtn>
        </div>
      </FrCard>
    </div>

    <!-- Add post modal -->
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
