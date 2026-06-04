<script setup lang="ts">
import { computed } from 'vue'
import {
  Home, Heart, BarChart2, Newspaper, Ellipsis,
  ChevronRight, ChevronDown, ArrowRight,
  RefreshCw, Share2, ShieldCheck, Percent,
  Receipt, Building2, Mail, Phone, X, Check, Copy, Info,
  CreditCard, Smartphone, ArrowLeftRight, UserRound, Hand,
  Droplets, Settings2, Sun, Trophy, MessageCircle,
} from 'lucide-vue-next'

const iconMap: Record<string, unknown> = {
  home: Home, heart: Heart, chart: BarChart2, news: Newspaper, more: Ellipsis,
  chevR: ChevronRight, chevD: ChevronDown, arrowR: ArrowRight,
  repeat: RefreshCw, share: Share2, shield: ShieldCheck, percent: Percent,
  receipt: Receipt, building: Building2, mail: Mail, phone: Phone,
  close: X, check: Check, copy: Copy, info: Info,
  blik: Smartphone, card: CreditCard, transfer: ArrowLeftRight,
  body: UserRound, hand: Hand, drop: Droplets, gear: Settings2,
  sun: Sun, horse: Trophy, speak: MessageCircle,
}

const props = defineProps<{
  name: string
  size?: number
  color?: string
  strokeWidth?: number
}>()

const component = computed(() => iconMap[props.name] ?? Info)
</script>

<template>
  <component
    :is="component"
    :size="size ?? 20"
    :color="color"
    :stroke-width="strokeWidth ?? 2"
    style="flex-shrink: 0"
  />
</template>
