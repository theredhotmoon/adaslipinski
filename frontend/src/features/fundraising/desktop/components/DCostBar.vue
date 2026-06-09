<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import InlineNumber from '@/features/admin/components/InlineNumber.vue'
import { useUpdateBeneficiary } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData, zl } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()
defineProps<{ big?: boolean }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const b = computed(() => siteData?.value?.budget ?? staticData.budget)
const nfzPct = computed(() => Math.round((b.value.nfz / b.value.total) * 100))

const { mutate: patchBeneficiary } = useUpdateBeneficiary()
</script>
<template>
  <div :style="{ background: c.surface, border: `1px solid ${c.line}`, borderRadius: '18px', padding: big ? '26px' : '20px' }">
    <div class="flex justify-between items-baseline mb-3">
      <span :style="{ fontWeight: 800, color: c.ink, fontSize: '16px' }">{{ t('d.costBarTitle') }}</span>
      <span :style="{ fontWeight: 900, color: c.ink, fontSize: big ? '28px' : '22px', fontFamily: dt.font, whiteSpace: 'nowrap' }">{{ zl(b.total) }}</span>
    </div>
    <div :style="{ height: '18px', borderRadius: '9px', background: c.surfaceAlt, overflow: 'hidden', display: 'flex' }">
      <div :style="{ width: nfzPct + '%', background: c.inkSoft }" />
      <div :style="{ flex: 1, background: c.primary }" />
    </div>
    <div class="flex justify-between mt-3" :style="{ fontSize: '14px' }">
      <span :style="{ color: c.inkSoft }">
        <InlineNumber :value="b.nfz" :min="0" @save="patchBeneficiary({ nfz_monthly_pln: $event })" :style="{ fontWeight: 800, color: c.ink }">{{ zl(b.nfz) }}</InlineNumber>
        {{ t('common.nfzCovers') }}
      </span>
      <span :style="{ color: c.primaryDeep, fontWeight: 800 }">{{ t('d.gapMonthly', { amount: zl(b.gap) }) }}</span>
    </div>
  </div>
</template>
