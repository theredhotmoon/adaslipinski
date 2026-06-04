<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import FrIcon from '../../components/FrIcon.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateFoundation } from '@/features/admin/useCmsApi'
import { dt } from '../desktopTheme'
import { data as staticData } from '../../data'
import type { SiteContent } from '../../types'
const { c } = dt

const siteData = inject<Ref<SiteContent>>('siteData')
const d = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const { mutate: patchFoundation } = useUpdateFoundation()
</script>
<template>
  <footer :style="{ background: c.ink, color: 'rgba(255,255,255,0.86)', marginTop: '70px' }">
    <div class="max-w-[1200px] mx-auto px-7 py-[52px] pb-10">
      <div class="grid grid-cols-[1.4fr_1fr_1fr] gap-10">
        <div>
          <div class="flex items-center gap-2.5 mb-3.5">
            <span :style="{ width: '34px', height: '34px', borderRadius: '10px', background: c.primary, display: 'grid', placeItems: 'center' }">
              <FrIcon name="heart" :size="18" :color="c.primaryInk" />
            </span>
            <span :style="{ fontWeight: 900, fontSize: '19px', color: '#fff' }">Adaś Lipiński</span>
          </div>
          <p :style="{ margin: 0, fontSize: '14.5px', lineHeight: 1.6, maxWidth: '360px', color: 'rgba(255,255,255,0.7)' }">
            Zbiórka prowadzona przez {{ d.name }}. Wpłaty trafiają na subkonto z dopiskiem „{{ d.cel }}" — nie na konto prywatne.
          </p>
        </div>
        <div>
          <div :style="{ fontWeight: 800, color: '#fff', fontSize: '14px', marginBottom: '12px' }">Fundacja</div>
          <div :style="{ fontSize: '14px', marginBottom: '7px' }">
            <span :style="{ color: 'rgba(255,255,255,0.5)' }">KRS: </span>
            <InlineText :value="d.krs" @save="patchFoundation({ krs: $event })" :style="{ fontFamily: 'ui-monospace, Menlo, monospace', color: 'rgba(255,255,255,0.86)' }" />
          </div>
          <div :style="{ fontSize: '14px', marginBottom: '7px' }">
            <span :style="{ color: 'rgba(255,255,255,0.5)' }">NIP: </span>
            <InlineText :value="d.nip" @save="patchFoundation({ nip: $event })" :style="{ fontFamily: 'ui-monospace, Menlo, monospace', color: 'rgba(255,255,255,0.86)' }" />
          </div>
          <div :style="{ fontSize: '14px', marginBottom: '7px' }">
            <span :style="{ color: 'rgba(255,255,255,0.5)' }">REGON: </span>
            <InlineText :value="d.regon" @save="patchFoundation({ regon: $event })" :style="{ fontFamily: 'ui-monospace, Menlo, monospace', color: 'rgba(255,255,255,0.86)' }" />
          </div>
        </div>
        <div>
          <div :style="{ fontWeight: 800, color: '#fff', fontSize: '14px', marginBottom: '12px' }">Linki</div>
          <a v-for="l in [...d.links.map((x: { label: string }) => x.label), 'Polityka prywatności']" :key="l" href="#" :style="{ display: 'block', fontSize: '14px', marginBottom: '8px', color: 'rgba(255,255,255,0.7)', textDecoration: 'none' }">{{ l }}</a>
        </div>
      </div>
      <div :style="{ marginTop: '36px', paddingTop: '22px', borderTop: '1px solid rgba(255,255,255,0.12)', fontSize: '13px', color: 'rgba(255,255,255,0.5)' }">
        © 2026 adaslipinski.pl · Zbiórka zgodna z prawem · {{ d.email }}
      </div>
    </div>
  </footer>
</template>
