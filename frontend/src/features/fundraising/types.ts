export interface BudgetItemData {
  dbId: number
  id: string
  name: string
  freq: string
  cost: number
  note: string
  icon: string
}

export interface ProgressPost {
  id: number
  date: string
  tag: string
  title: string
  body: string
  img: string
  imgUrl?: string
  amount: number | null
}

export interface ExpenseData {
  id: number
  date: string
  desc: string
  amount: number
  place: string
  invoice: boolean
  invoiceUrl?: string
}

export interface FaqItem {
  id: number
  q: string
  a: string
}

export interface FoundationData {
  name: string
  krs: string
  nip: string
  regon: string
  cel: string
  address: string
  web: string
  blikPhone: string
  email: string
  phone: string
  accounts: { cur: string; iban: string }[]
  links: { label: string; href: string }[]
}

export interface MilestoneData {
  id: number
  year: string
  text: string
}

export interface PartnerData {
  id: number
  name: string
  logoUrl?: string
}

export interface SiteContent {
  child: {
    name: string
    fullName: string
    age: number
    diagnosis: string
    diagnosisPlain: string
    heroKicker: string
    heroTitle: string
    heroSubtitle: string
    ctaLabel: string
    ctaBarLabel: string
    recurringDefault: boolean
    heroImageUrl?: string
  }
  budget: {
    total: number
    nfz: number
    gap: number
    items: BudgetItemData[]
  }
  milestones: MilestoneData[]
  progress: ProgressPost[]
  expenses: ExpenseData[]
  yearSummary: { year: number; in: number; out: number; left: number; tax: number } | null
  faq: FaqItem[]
  partners: PartnerData[]
  foundation: FoundationData
  amounts: number[]
  testimonials: { id: number; quote: string; authorName: string; authorRole: string; photoUrl?: string }[]
  gallery: { id: number; url: string }[]
}
