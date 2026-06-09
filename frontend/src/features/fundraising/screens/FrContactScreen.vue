<script setup lang="ts">
import { ref, inject, computed } from 'vue'
import type { Ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMutation } from '@tanstack/vue-query'
import FrCard from '../components/FrCard.vue'
import FrBtn from '../components/FrBtn.vue'
import FrIcon from '../components/FrIcon.vue'
import FrSectionLabel from '../components/FrSectionLabel.vue'
import InlineText from '@/features/admin/components/InlineText.vue'
import { useUpdateFoundation } from '@/features/admin/useCmsApi'
import { siteConfig } from '@/config/site'
import { theme, data as staticData } from '../data'
import type { SiteContent } from '../types'

const { c, r, f } = theme
const { t } = useI18n()

const siteData = inject<Ref<SiteContent>>('siteData')
const foundation = computed(() => siteData?.value?.foundation ?? staticData.foundation as any)
const contactEmail = computed(() => foundation.value.email ?? staticData.contact.email)
const contactPhone = computed(() => foundation.value.phone ?? staticData.contact.phone)
const abroadBody = computed(() => t('contact.abroadBody', { name: siteConfig.beneficiary.name }))

const { mutate: patchFoundation } = useUpdateFoundation()

const name = ref('')
const email = ref('')
const message = ref('')
const sent = ref(false)

const { mutate: send, isPending } = useMutation({
  mutationFn: async (payload: { name: string; email: string; message: string }) => {
    await new Promise((res) => setTimeout(res, 700))
    return payload
  },
  onSuccess: () => { sent.value = true },
})
</script>

<template>
  <div style="padding-bottom: 28px;">
    <div style="padding: 16px 18px 6px;">
      <div :style="{ fontSize: '12.5px', fontWeight: 800, color: c.primary, textTransform: 'uppercase', letterSpacing: '0.05em', marginBottom: '6px' }">{{ t('contact.kicker') }}</div>
      <h1 :style="{ margin: 0, fontFamily: f.heading, fontWeight: f.hWeight, fontSize: '30px', lineHeight: 1.08, letterSpacing: f.hLetter, color: c.ink }">{{ t('contact.title') }}</h1>
      <p :style="{ margin: '10px 0 0', color: c.inkSoft, fontSize: '15px', lineHeight: 1.5 }">{{ t('contact.subtitle') }}</p>
    </div>

    <div style="padding: 16px 18px 0; display: flex; flex-direction: column; gap: 10px;">
      <FrCard :style="{ display: 'flex', alignItems: 'center', gap: '12px' }">
        <div :style="{ width: '44px', height: '44px', borderRadius: '12px', background: c.primarySoft, display: 'grid', placeItems: 'center' }">
          <FrIcon name="mail" :size="22" :color="c.primary" />
        </div>
        <div>
          <div :style="{ fontSize: '12px', color: c.inkSoft }">{{ t('contact.email') }}</div>
          <InlineText :value="contactEmail" @save="patchFoundation({ email: $event })" :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }" />
        </div>
      </FrCard>
      <FrCard :style="{ display: 'flex', alignItems: 'center', gap: '12px' }">
        <div :style="{ width: '44px', height: '44px', borderRadius: '12px', background: c.primarySoft, display: 'grid', placeItems: 'center' }">
          <FrIcon name="phone" :size="22" :color="c.primary" />
        </div>
        <div>
          <div :style="{ fontSize: '12px', color: c.inkSoft }">{{ t('contact.foundationPhone') }}</div>
          <InlineText :value="contactPhone" @save="patchFoundation({ phone: $event })" :style="{ fontWeight: 800, color: c.ink, fontSize: '15px' }" />
        </div>
      </FrCard>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrSectionLabel>{{ t('contact.quickMessage') }}</FrSectionLabel>
      <FrCard>
        <div v-if="sent" :style="{ textAlign: 'center', padding: '12px 0', color: c.primary, fontWeight: 700, fontSize: '15px', display: 'flex', justifyContent: 'center', alignItems: 'center', gap: '8px' }">
          <FrIcon name="check" :size="20" :color="c.primary" /> {{ t('contact.sent') }}
        </div>
        <template v-else>
          <input
            v-model="name"
            :placeholder="t('contact.namePlaceholder')"
            :style="{ width: '100%', boxSizing: 'border-box', marginBottom: '8px', padding: '12px 13px', fontFamily: 'inherit', fontSize: '14.5px', color: c.ink, background: c.surfaceAlt, border: `1px solid ${c.line}`, borderRadius: (r * 0.7) + 'px', outline: 'none' }"
          />
          <input
            v-model="email"
            :placeholder="t('contact.email')"
            :style="{ width: '100%', boxSizing: 'border-box', marginBottom: '8px', padding: '12px 13px', fontFamily: 'inherit', fontSize: '14.5px', color: c.ink, background: c.surfaceAlt, border: `1px solid ${c.line}`, borderRadius: (r * 0.7) + 'px', outline: 'none' }"
          />
          <textarea
            v-model="message"
            :placeholder="t('contact.messagePlaceholder')"
            rows="4"
            :style="{ width: '100%', boxSizing: 'border-box', padding: '12px 13px', fontFamily: 'inherit', fontSize: '14.5px', color: c.ink, background: c.surfaceAlt, border: `1px solid ${c.line}`, borderRadius: (r * 0.7) + 'px', outline: 'none', resize: 'none' }"
          />
          <div style="height: 10px;" />
          <FrBtn variant="primary" :full="true" :style="{ borderRadius: (r * 0.85) + 'px' }" @click="send({ name, email, message })">
            {{ isPending ? t('contact.sending') : t('contact.send') }}
          </FrBtn>
        </template>
      </FrCard>
    </div>

    <div style="padding: 16px 18px 0;">
      <FrSectionLabel>{{ t('contact.abroadTitle') }}</FrSectionLabel>
      <FrCard :style="{ fontSize: '13.5px', color: c.inkSoft, lineHeight: 1.55 }" v-html="abroadBody" />
    </div>
  </div>
</template>
