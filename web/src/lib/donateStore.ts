import { atom } from 'nanostores'

// Cross-island shared state. Each donate trigger (hero CTA, sticky bar, a budget
// item's "fund this") is a SEPARATE Vue island, so they can't share a Vue app
// instance or provide/inject. A framework-agnostic nanostore bridges them: triggers
// write, the single <DonateModal> island subscribes. See the skill's island pitfalls.

export interface DonateIntent {
  open: boolean
  amount: number
  freq: 'once' | 'monthly'
  item: string | null
}

export const donate = atom<DonateIntent>({
  open: false,
  amount: 100,
  freq: 'monthly',
  item: null,
})

export function openDonate(payload: Partial<Omit<DonateIntent, 'open'>> = {}): void {
  donate.set({
    open: true,
    amount: payload.amount ?? 100,
    freq: payload.freq ?? 'monthly',
    item: payload.item ?? null,
  })
}

export function closeDonate(): void {
  donate.set({ ...donate.get(), open: false })
}
