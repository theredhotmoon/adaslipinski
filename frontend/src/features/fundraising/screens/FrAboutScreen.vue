<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrCard from '../components/FrCard.vue'
import FrBtn from '../components/FrBtn.vue'
import FrIcon from '../components/FrIcon.vue'
import FrPh from '../components/FrPh.vue'
import FrSectionLabel from '../components/FrSectionLabel.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import AdminAdd from '@/features/admin/components/AdminAdd.vue'
import AdminDelete from '@/features/admin/components/AdminDelete.vue'
import AdminFormModal from '@/features/admin/components/AdminFormModal.vue'
import {
  useUpdateBeneficiary,
  useUpdateMilestone, useCreateMilestone, useDeleteMilestone,
} from '@/features/admin/useCmsApi'
import { theme, data as staticData } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const emit = defineEmits<{ donate: [{ amount: number; freq: 'once' | 'monthly' }] }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const child = computed(() => siteData?.value?.child ?? staticData.child as any)
const milestones = computed<SiteContent['milestones']>(
  () => siteData?.value?.milestones ?? staticData.milestones.map((m, i) => ({ id: i + 1, year: m.year, text: m.text }))
)

const { mutate: patchBeneficiary } = useUpdateBeneficiary()
const { mutate: updateMilestone } = useUpdateMilestone()
const { mutate: createMilestone, isPending: creating } = useCreateMilestone()
const { mutate: deleteMilestone } = useDeleteMilestone()

const showAdd = ref(false)
const newMs = ref({ year: '', label: '' })
function submitMilestone() {
  if (!newMs.value.year || !newMs.value.label) return
  createMilestone({ year: newMs.value.year, label: newMs.value.label }, {
    onSuccess: () => { showAdd.value = false; newMs.value = { year: '', label: '' } }
  })
}
</script>

<template>
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">Historia</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">Poznaj Adasia</h1>
    </div>

    <div style="padding: 14px 18px 0;">
      <FrPh label="Adaś podczas terapii — uśmiech, ruch" :h="200" />
      <div :style="{ marginTop: '16px', color: c.ink, fontSize: '15.5px', lineHeight: 1.65 }">
        <p style="margin: 0 0 12px;">Adaś urodził się z niedotlenieniem. Pierwsze tygodnie spędził na OIOM-ie, a kilka miesięcy później usłyszeliśmy diagnozę, która zmieniła nasze życie.</p>
        <p style="margin: 0 0 12px;">Dziś ma {{ child.age }} lat, uwielbia bajki o piesku i śmieje się na całe gardło, gdy tata podrzuca go do góry. Ale codzienność to godziny ćwiczeń — bo każdy ruch musi wypracować od zera.</p>
        <p style="margin: 0;">Wierzymy, że konsekwentna rehabilitacja da mu maksymalną samodzielność. I robimy wszystko, żeby mu ją zapewnić.</p>
      </div>
    </div>

    <div style="padding: 18px 18px 0;">
      <FrCard>
        <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.04em' }">Diagnoza</div>
        <InlineText
          tag="div"
          :value="child.diagnosis"
          :multiline="true"
          @save="patchBeneficiary({ diagnosis: $event })"
          :style="{ marginTop: '6px', fontWeight: 700, color: c.ink, fontSize: '16px', lineHeight: 1.35 }"
        />
        <InlineText
          tag="p"
          :value="child.diagnosisPlain"
          :multiline="true"
          @save="patchBeneficiary({ diagnosis_plain: $event })"
          :style="{ margin: '8px 0 0', color: c.inkSoft, fontSize: '14px', lineHeight: 1.5 }"
        />
      </FrCard>
    </div>

    <!-- Quote -->
    <div style="padding: 18px 18px 0;">
      <FrCard :style="{ background: c.primarySoft, border: 'none' }">
        <div :style="{ fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '18px', color: c.ink, lineHeight: 1.4, letterSpacing: f.hLetter }">
          „Adaś robi postępy, jakich na początku nikt mu nie wróżył. Regularna terapia ma tu kluczowe znaczenie."
        </div>
        <div :style="{ marginTop: '12px', display: 'flex', alignItems: 'center', gap: '10px' }">
          <FrPh label="foto" :h="42" :radius="999" style="width: 42px; flex-shrink: 0;" />
          <div style="font-size: 12.5px;">
            <div :style="{ fontWeight: 800, color: c.ink }">mgr Anna Wójcik</div>
            <div :style="{ color: c.inkSoft }">fizjoterapeutka NDT-Bobath, Centrum Ruch</div>
          </div>
        </div>
      </FrCard>
    </div>

    <!-- Milestones -->
    <div style="padding: 24px 18px 0;">
      <FrSectionLabel>Kamienie milowe</FrSectionLabel>
      <div style="display: flex; flex-direction: column;">
        <div v-for="(m, i) in milestones" :key="i" style="display: flex; gap: 14px;">
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div :style="{ width: '14px', height: '14px', borderRadius: '999px', background: c.primary, marginTop: '3px', flexShrink: 0, border: `3px solid ${c.primarySoft}` }" />
            <div v-if="i < milestones.length - 1" :style="{ width: '2px', flex: 1, background: c.line, minHeight: '28px' }" />
          </div>
          <div style="padding-bottom: 18px; flex: 1;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <InlineText :value="m.year" @save="updateMilestone({ id: m.id, year: $event })" :style="{ fontWeight: 800, color: c.primary, fontSize: '13px' }" />
              <AdminDelete label="kamień milowy" @click="deleteMilestone(m.id)" />
            </div>
            <InlineText tag="div" :value="m.text" @save="updateMilestone({ id: m.id, label: $event })" :style="{ color: c.ink, fontSize: '15px', marginTop: '1px' }" />
          </div>
        </div>
      </div>
      <AdminAdd label="Dodaj kamień milowy" @click="showAdd = true" />
    </div>

    <!-- Gallery -->
    <div style="padding: 8px 18px 0;">
      <FrSectionLabel>Z codzienności</FrSectionLabel>
      <div :style="{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '8px' }">
        <FrPh v-for="(l, i) in ['Basen — terapia w wodzie', 'Hipoterapia', 'Pierwsze kroki w pionizatorze', 'Śmiech z tatą']" :key="i" :label="l" :h="110" />
      </div>
    </div>

    <div style="padding: 22px 18px 0;">
      <FrBtn variant="primary" :full="true" size="lg" :style="{ borderRadius: r + 'px' }" @click="emit('donate', { amount: 100, freq: 'monthly' })">
        <FrIcon name="heart" :size="18" :color="c.primaryInk" /> Pomóż Adasiowi co miesiąc
      </FrBtn>
    </div>

    <!-- Add milestone modal -->
    <AdminFormModal title="Dodaj kamień milowy" :open="showAdd" :saving="creating" @close="showAdd = false" @save="submitMilestone">
      <label class="block mb-3">
        <span class="text-xs font-bold text-gray-500 mb-1 block">Rok</span>
        <input v-model="newMs.year" maxlength="4" placeholder="2026" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
      <label class="block">
        <span class="text-xs font-bold text-gray-500 mb-1 block">Opis</span>
        <input v-model="newMs.label" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-emerald-400" />
      </label>
    </AdminFormModal>
  </div>
</template>
