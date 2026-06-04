<script setup lang="ts">
import DPh from './DPh.vue'
import { dt } from '../desktopTheme'
const { c } = dt
defineProps<{ light?: boolean }>()
</script>
<template>
  <div class="flex gap-[18px] items-start">
    <div>
      <div :style="{ fontFamily: dt.font, fontWeight: 800, fontSize: '23px', color: light ? '#fff' : c.ink, lineHeight: 1.4, letterSpacing: '-0.01em' }">
        „Adaś robi postępy, jakich na początku nikt mu nie wróżył. Regularna terapia ma tu kluczowe znaczenie."
      </div>
      <div class="flex items-center gap-3 mt-4">
        <DPh label="foto" :h="48" :radius="999" style="width: 48px; flex-shrink: 0;" />
        <div :style="{ fontSize: '14px' }">
          <div :style="{ fontWeight: 800, color: light ? '#fff' : c.ink }">mgr Anna Wójcik</div>
          <div :style="{ color: light ? 'rgba(255,255,255,0.7)' : c.inkSoft }">fizjoterapeutka NDT-Bobath, Centrum Ruch</div>
        </div>
      </div>
    </div>
  </div>
</template>
