import { useMutation, useQueryClient } from '@tanstack/vue-query'
import { api } from '@/lib/api'

const QK = ['cms-site']

function useInvalidating<T>(fn: (data: T) => Promise<unknown>) {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: fn,
    onSuccess: () => qc.invalidateQueries({ queryKey: QK }),
  })
}

// ── Beneficiary ───────────────────────────────────────────────────────────────
export const useUpdateBeneficiary = () =>
  useInvalidating((data: Record<string, unknown>) =>
    api.patch('/admin/beneficiary', data))

// ── Foundation ────────────────────────────────────────────────────────────────
export const useUpdateFoundation = () =>
  useInvalidating((data: Record<string, unknown>) =>
    api.patch('/admin/foundation', data))

// ── Progress posts ────────────────────────────────────────────────────────────
export const useUpdateProgress = () =>
  useInvalidating(({ id, ...data }: { id: number } & Record<string, unknown>) =>
    api.put(`/admin/progress/${id}`, data))

export const useCreateProgress = () =>
  useInvalidating((data: Record<string, unknown>) =>
    api.post('/admin/progress', data))

export const useDeleteProgress = () =>
  useInvalidating((id: number) =>
    api.delete(`/admin/progress/${id}`))

// ── Budget items ──────────────────────────────────────────────────────────────
export const useUpdateBudgetItem = () =>
  useInvalidating(({ id, ...data }: { id: number } & Record<string, unknown>) =>
    api.patch(`/admin/budget-items/${id}`, data))

// ── Milestones ────────────────────────────────────────────────────────────────
export const useUpdateMilestone = () =>
  useInvalidating(({ id, ...data }: { id: number } & Record<string, unknown>) =>
    api.put(`/admin/milestones/${id}`, data))

export const useCreateMilestone = () =>
  useInvalidating((data: { year: string; label: string }) =>
    api.post('/admin/milestones', data))

export const useDeleteMilestone = () =>
  useInvalidating((id: number) =>
    api.delete(`/admin/milestones/${id}`))

// ── Expenses ──────────────────────────────────────────────────────────────────
export const useUpdateExpense = () =>
  useInvalidating(({ id, ...data }: { id: number } & Record<string, unknown>) =>
    api.put(`/admin/expenses/${id}`, data))

export const useCreateExpense = () =>
  useInvalidating((data: Record<string, unknown>) =>
    api.post('/admin/expenses', data))

export const useDeleteExpense = () =>
  useInvalidating((id: number) =>
    api.delete(`/admin/expenses/${id}`))

// ── FAQ ───────────────────────────────────────────────────────────────────────
export const useUpdateFaq = () =>
  useInvalidating(({ id, ...data }: { id: number } & Record<string, unknown>) =>
    api.put(`/admin/faq/${id}`, data))

export const useCreateFaq = () =>
  useInvalidating((data: { question: string; answer: string }) =>
    api.post('/admin/faq', data))

export const useDeleteFaq = () =>
  useInvalidating((id: number) =>
    api.delete(`/admin/faq/${id}`))

// ── Partners ──────────────────────────────────────────────────────────────────
export const useCreatePartner = () =>
  useInvalidating((data: { name: string }) =>
    api.post('/admin/partners', data))

export const useDeletePartner = () =>
  useInvalidating((id: number) =>
    api.delete(`/admin/partners/${id}`))
