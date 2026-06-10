<script setup lang="ts">
import { inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import DPh from './DPh.vue'
import AdminImageUpload from '@/features/admin/components/AdminImageUpload.vue'
import { useUpdateTestimonial } from '@/features/admin/useCmsApi'
import { siteConfig } from '@/config/site'
import { dt } from '../desktopTheme'
import type { SiteContent } from '../../types'
const { c } = dt
const { t } = useI18n()
const name = siteConfig.beneficiary.name
defineProps<{ light?: boolean }>()

const siteData = inject<Ref<SiteContent>>('siteData')
const testimonial = computed(() => siteData?.value?.testimonials?.[0])
const { mutate: updateTestimonial } = useUpdateTestimonial()
</script>
<template>
  <div class="flex gap-[18px] items-start">
    <div>
      <div :style="{ fontFamily: dt.font, fontWeight: 800, fontSize: '23px', color: light ? '#fff' : c.ink, lineHeight: 1.4, letterSpacing: '-0.01em' }">
        {{ testimonial?.quote ?? t('about.quote', { name }) }}
      </div>
      <div class="flex items-center gap-3 mt-4">
        <DPh label="foto" :src="testimonial?.photoUrl" :h="48" :radius="999" style="width: 48px; flex-shrink: 0;" />
        <div :style="{ fontSize: '14px' }">
          <div :style="{ fontWeight: 800, color: light ? '#fff' : c.ink }">{{ testimonial?.authorName ?? t('about.quoteAuthor') }}</div>
          <div :style="{ color: light ? 'rgba(255,255,255,0.7)' : c.inkSoft }">{{ testimonial?.authorRole ?? t('about.quoteRole') }}</div>
        </div>
        <AdminImageUpload v-if="testimonial?.id" label="" :alt="testimonial?.authorName" @uploaded="updateTestimonial({ id: testimonial!.id, photo_id: $event.id })" />
      </div>
    </div>
  </div>
</template>
