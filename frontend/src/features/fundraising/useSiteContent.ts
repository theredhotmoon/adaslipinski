import { useQuery } from '@tanstack/vue-query'
import { useI18n } from 'vue-i18n'
import { api } from '@/lib/api'
import { data as staticData, theme } from './data'
import type { SiteContent } from './types'

// Static fallback so the page renders instantly before the API responds
const fallback: SiteContent = {
  child: {
    name:             staticData.child.name,
    fullName:         staticData.child.fullName,
    age:              staticData.child.age,
    diagnosis:        staticData.child.diagnosis,
    diagnosisPlain:   staticData.child.diagnosisPlain,
    heroKicker:       theme.copy.heroKicker,
    heroTitle:        theme.copy.heroTitle,
    heroSubtitle:     theme.copy.heroSub,
    ctaLabel:         theme.copy.cta,
    ctaBarLabel:      theme.copy.ctaBar,
    recurringDefault: theme.copy.recurringDefault,
  },
  budget: {
    total: staticData.budget.total,
    nfz:   staticData.budget.nfz,
    gap:   staticData.budget.gap,
    items: staticData.budget.items.map((it, i) => ({
      dbId: i + 1, id: it.id, name: it.name, freq: it.freq, cost: it.cost, note: it.note, icon: it.icon,
    })),
  },
  milestones: staticData.milestones.map((m, i) => ({ id: i + 1, year: m.year, text: m.text })),
  progress:   staticData.progress.map(p => ({
    id: parseInt(p.id.replace('p', '')), date: p.date, tag: p.tag,
    title: p.title, body: p.body, img: p.img, amount: p.amount,
  })),
  expenses: staticData.expenses.map((e, i) => ({
    id: i + 1, date: e.date, desc: e.desc, amount: e.amount, place: e.place, invoice: e.invoice,
  })),
  yearSummary: {
    year: staticData.yearSummary.year as unknown as number,
    in:   staticData.yearSummary.in,
    out:  staticData.yearSummary.out,
    left: staticData.yearSummary.left,
    tax:  staticData.yearSummary.tax,
  },
  faq:      staticData.faq.map((f, i) => ({ id: i + 1, q: f.q, a: f.a })),
  partners: staticData.partners.map((name, i) => ({ id: i + 1, name })),
  foundation: {
    name:      staticData.foundation.name,
    krs:       staticData.foundation.krs,
    nip:       staticData.foundation.nip,
    regon:     staticData.foundation.regon,
    cel:       staticData.foundation.cel,
    address:   staticData.foundation.address,
    web:       staticData.foundation.web,
    blikPhone: staticData.foundation.blikPhone,
    email:     staticData.contact.email,
    phone:     staticData.contact.phone,
    accounts:  staticData.foundation.accounts.map(a => ({ cur: a.cur, iban: a.iban })),
    links:     staticData.foundation.links.map(l => ({ label: l.label, href: l.href })),
  },
  amounts:      [...staticData.amounts],
  testimonials: [],
  gallery:      [],
}

export function useSiteContent() {
  const { locale } = useI18n()

  return useQuery<SiteContent>({
    // `locale` is a ref — including it makes the key reactive, so switching
    // language refetches the content in the new locale (and caches per locale).
    queryKey: ['cms-site', locale],
    queryFn: () => api.get('/cms/site', { params: { lang: locale.value } }).then(r => r.data),
    // placeholderData (not initialData) shows the static fallback instantly while
    // ALWAYS fetching live CMS content — otherwise initialData + staleTime would
    // make the query treat the hardcoded fallback as fresh and skip the refetch.
    placeholderData: fallback,
    staleTime: 1000 * 30,
  })
}
