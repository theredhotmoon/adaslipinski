<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrCard from '../components/FrCard.vue'
import FrIcon from '../components/FrIcon.vue'
import FrPh from '../components/FrPh.vue'
import FrSectionLabel from '../components/FrSectionLabel.vue'
import FrCopyField from '../components/FrCopyField.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateFoundation } from '@/features/admin/useCmsApi'
import { theme, data as staticData } from '../data'
import type { SiteContent } from '../types'

const { c, f } = theme
const siteData = inject<Ref<SiteContent>>('siteData')
const d = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)

const { mutate: patchFoundation } = useUpdateFoundation()
</script>

<template>
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">Wiarygodność</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">Fundacja</h1>
      <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.5 }">Nie zbieramy na konto prywatne. Oto dokładnie, dokąd trafiają pieniądze.</p>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrCard>
        <div :style="{ display: 'flex', gap: '12px', alignItems: 'center' }">
          <FrPh label="logo" :h="54" :radius="12" style="width: 54px; flex-shrink: 0;" />
          <InlineText
            tag="div"
            :value="d.name"
            :multiline="true"
            @save="patchFoundation({ name: $event })"
            :style="{ fontWeight: 800, color: c.ink, fontSize: '16px', lineHeight: 1.3 }"
          />
        </div>
        <div :style="{ marginTop: '14px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '10px' }">
          <div>
            <div :style="{ fontSize: '11px', color: c.inkSoft, fontWeight: 600 }">KRS</div>
            <InlineText :value="d.krs" @save="patchFoundation({ krs: $event })" :style="{ fontWeight: 700, color: c.ink, fontSize: '14px', fontFamily: `ui-monospace, Menlo, monospace` }" />
          </div>
          <div>
            <div :style="{ fontSize: '11px', color: c.inkSoft, fontWeight: 600 }">NIP</div>
            <InlineText :value="d.nip" @save="patchFoundation({ nip: $event })" :style="{ fontWeight: 700, color: c.ink, fontSize: '14px', fontFamily: `ui-monospace, Menlo, monospace` }" />
          </div>
          <div>
            <div :style="{ fontSize: '11px', color: c.inkSoft, fontWeight: 600 }">REGON</div>
            <InlineText :value="d.regon" @save="patchFoundation({ regon: $event })" :style="{ fontWeight: 700, color: c.ink, fontSize: '14px', fontFamily: `ui-monospace, Menlo, monospace` }" />
          </div>
          <div>
            <div :style="{ fontSize: '11px', color: c.inkSoft, fontWeight: 600 }">Subkonto</div>
            <InlineText :value="d.cel" @save="patchFoundation({ cel: $event })" :style="{ fontWeight: 700, color: c.ink, fontSize: '14px', fontFamily: `ui-monospace, Menlo, monospace` }" />
          </div>
        </div>
      </FrCard>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrCard :style="{ background: c.primarySoft, border: 'none', display: 'flex', gap: '10px' }">
        <FrIcon name="shield" :size="24" :color="c.primary" style="flex-shrink: 0;" />
        <div :style="{ fontSize: '13.5px', color: c.ink, lineHeight: 1.55 }">
          Wszystkie wpłaty z dopiskiem <b>433/L</b> trafiają na wydzielone subkonto fundacji. Środki wypłacane są wyłącznie na podstawie faktur na rehabilitację Adasia. <b>Rodzice nie mogą nimi dysponować prywatnie.</b>
        </div>
      </FrCard>
    </div>

    <div style="padding: 18px 18px 0;">
      <FrSectionLabel>Oficjalne źródła</FrSectionLabel>
      <FrCard :pad="0" :style="{ overflow: 'hidden' }">
        <button
          v-for="(l, i) in d.links"
          :key="i"
          :style="{ width: '100%', padding: '14px', borderTop: i ? `1px solid ${c.line}` : 'none', background: 'transparent', border: 'none', cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '10px', fontFamily: 'inherit' }"
        >
          <FrIcon name="building" :size="19" :color="c.primary" />
          <span :style="{ flex: 1, textAlign: 'left', fontWeight: 700, color: c.ink, fontSize: '14px' }">{{ l.label }}</span>
          <FrIcon name="chevR" :size="16" :color="c.inkSoft" />
        </button>
      </FrCard>
    </div>

    <div style="padding: 18px 18px 0;">
      <FrSectionLabel>Konta do przelewu</FrSectionLabel>
      <FrCopyField v-for="a in d.accounts" :key="a.cur" :label="a.cur" :value="a.iban" />
      <FrCopyField label="Tytuł przelewu" :value="d.cel" :mono="false" />
    </div>
  </div>
</template>
