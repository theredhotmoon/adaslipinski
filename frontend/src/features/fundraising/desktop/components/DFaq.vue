<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FrIcon from '../../components/FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import { useUpdateFaq, useCreateFaq, useDeleteFaq } from '@/features/admin/useCmsApi'
import { useAuthStore } from '@/features/auth/store'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()
const auth = useAuthStore()

const siteData = inject<Ref<SiteContent>>('siteData')
const items = computed<SiteContent['faq']>(
  () => siteData?.value?.faq ?? staticData.faq.map((f, i) => ({ id: i + 1, q: f.q, a: f.a }))
)

const open = ref(0)
const { mutate: updateFaq } = useUpdateFaq()
const { mutate: createFaq, isPending: creating } = useCreateFaq()
const { mutate: deleteFaq } = useDeleteFaq()

const showAdd = ref(false)
const newFaq = ref({ question: '', answer: '' })
function submitFaq() {
  if (!newFaq.value.question || !newFaq.value.answer) return
  createFaq({ ...newFaq.value }, {
    onSuccess: () => { showAdd.value = false; newFaq.value = { question: '', answer: '' } }
  })
}
</script>
<template>
  <div class="grid gap-2.5 max-w-[820px] mx-auto">
    <div v-for="(it, i) in items" :key="it.id ?? i" :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '14px', overflow: 'hidden' }">
      <div :style="{ width: '100%', display: 'flex', alignItems: 'center', gap: '12px', padding: '17px 20px' }">
        <InlineText
          v-if="auth.isAuthenticated && it.id"
          :value="it.q"
          @save="updateFaq({ id: it.id, question: $event })"
          :style="{ flex: 1, fontWeight: 800, color: c.ink, fontSize: '16.5px' }"
        />
        <button v-else :style="{ flex: 1, textAlign: 'left', background: 'transparent', border: 'none', cursor: 'pointer', fontFamily: dt.font, fontWeight: 800, color: c.ink, fontSize: '16.5px', padding: 0 }" @click="open = open === i ? -1 : i">{{ it.q }}</button>
        <AdminDelete v-if="it.id" :label="t('admin.del.faq')" @click="deleteFaq(it.id)" />
        <button :style="{ background: 'transparent', border: 'none', cursor: 'pointer', transform: open === i ? 'rotate(180deg)' : 'none', transition: 'transform 0.2s' }" @click="open = open === i ? -1 : i">
          <FrIcon name="chevD" :size="20" :color="c.inkSoft" />
        </button>
      </div>
      <div v-if="open === i" :style="{ padding: '0 20px 18px', color: c.inkSoft, fontSize: '15px', lineHeight: 1.6 }">
        <InlineText v-if="auth.isAuthenticated && it.id" :value="it.a" :multiline="true" @save="updateFaq({ id: it.id, answer: $event })" :style="{ display: 'block' }" />
        <template v-else>{{ it.a }}</template>
      </div>
    </div>
    <div class="max-w-[300px]">
      <AdminAdd :label="t('home.addQuestion')" @click="showAdd = true" />
    </div>

    <AdminFormModal :title="t('home.addFaqTitle')" :open="showAdd" :saving="creating" @close="showAdd = false" @save="submitFaq">
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('home.faqQuestionLabel') }}</span>
        <input v-model="newFaq.question" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
      <label class="block">
        <span class="text-xs font-bold text-gray-500 mb-1 block">{{ t('home.faqAnswerLabel') }}</span>
        <textarea v-model="newFaq.answer" rows="4" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400 resize-none" />
      </label>
    </AdminFormModal>
  </div>
</template>
