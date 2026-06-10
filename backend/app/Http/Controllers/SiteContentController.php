<?php
namespace App\Http\Controllers;

use App\Models\{Beneficiary, BudgetItem, DonationAmount, Expense, FaqItem, Foundation, GalleryImage, Milestone, Partner, ProgressPost, Testimonial, YearSummary};
use Illuminate\Http\JsonResponse;

class SiteContentController extends Controller
{
    public function index(): JsonResponse
    {
        $beneficiary = Beneficiary::with('heroImage')->first();
        $foundation  = Foundation::with(['accounts', 'links'])->first();
        $budgetItems = BudgetItem::active()->ordered()->get();
        $totalBudget = $budgetItems->sum('cost_pln');
        $nfz         = $beneficiary?->nfz_monthly_pln ?? 0;
        $year        = YearSummary::orderByDesc('year')->first();

        return response()->json([
            'child' => $beneficiary ? [
                'name'           => $beneficiary->name,
                'fullName'       => $beneficiary->full_name,
                'age'            => $beneficiary->age,
                'diagnosis'      => $beneficiary->diagnosis,
                'diagnosisPlain' => $beneficiary->diagnosis_plain,
                'heroKicker'     => $beneficiary->hero_kicker,
                'heroTitle'      => $beneficiary->hero_title,
                'heroSubtitle'   => $beneficiary->hero_subtitle,
                'ctaLabel'       => $beneficiary->cta_label,
                'ctaBarLabel'    => $beneficiary->cta_bar_label,
                'recurringDefault' => $beneficiary->recurring_default,
                'heroImageUrl'   => $beneficiary->heroImage?->url,
            ] : null,

            'budget' => [
                'total' => $totalBudget,
                'nfz'   => $nfz,
                'gap'   => max(0, $totalBudget - $nfz),
                'items' => $budgetItems->map(fn ($it) => [
                    'dbId' => $it->id,
                    'id'   => $it->slug,
                    'name' => $it->name,
                    'freq' => $it->frequency,
                    'cost' => $it->cost_pln,
                    'note' => $it->note,
                    'icon' => $it->icon,
                ]),
            ],

            'milestones' => Milestone::ordered()->get()->map(fn ($m) => [
                'id'   => $m->id,
                'year' => $m->year,
                'text' => $m->label,
            ]),

            'progress' => ProgressPost::published()
                ->orderByDesc('published_at')
                ->with('image')
                ->get()
                ->map(fn ($p) => [
                    'id'     => $p->id,
                    'date'   => $p->published_at?->format('j F Y'),
                    'tag'    => $p->tag,
                    'title'  => $p->title,
                    'body'   => $p->body,
                    'img'    => $p->image_alt ?? $p->image?->alt_text,
                    'imgUrl' => $p->image?->url,
                    'amount' => $p->amount_pln,
                ]),

            'expenses' => Expense::orderByDesc('expense_date')->get()->map(fn ($e) => [
                'id'         => $e->id,
                'date'       => $e->expense_date->format('d.m.Y'),
                'desc'       => $e->description,
                'amount'     => $e->amount_pln,
                'place'      => $e->vendor,
                'invoice'    => $e->has_invoice,
                'invoiceUrl' => $e->invoice_url,
            ]),

            'yearSummary' => $year ? [
                'year' => $year->year,
                'in'   => $year->received_pln,
                'out'  => $year->spent_pln,
                'left' => $year->balance_pln,
                'tax'  => $year->tax_1_5_pln,
            ] : null,

            'faq' => FaqItem::active()->ordered()->get()->map(fn ($f) => [
                'id' => $f->id,
                'q'  => $f->question,
                'a'  => $f->answer,
            ]),

            'partners' => Partner::active()->ordered()->with('logo')->get()->map(fn ($p) => [
                'id'      => $p->id,
                'name'    => $p->name,
                'logoUrl' => $p->logo?->url,
            ]),

            'foundation' => $foundation ? [
                'name'      => $foundation->name,
                'krs'       => $foundation->krs,
                'nip'       => $foundation->nip,
                'regon'     => $foundation->regon,
                'cel'       => $foundation->cel,
                'address'   => $foundation->address,
                'web'       => $foundation->web,
                'blikPhone' => $foundation->blik_phone,
                'email'     => $foundation->email,
                'phone'     => $foundation->phone,
                'accounts'  => $foundation->accounts->map(fn ($a) => [
                    'cur'  => $a->currency,
                    'iban' => $a->iban,
                ]),
                'links' => $foundation->links->map(fn ($l) => [
                    'label' => $l->label,
                    'href'  => $l->url,
                ]),
            ] : null,

            'amounts' => DonationAmount::active()->ordered()->pluck('amount_pln'),

            'testimonials' => Testimonial::active()->with('photo')->get()->map(fn ($t) => [
                'id'         => $t->id,
                'quote'      => $t->quote_text,
                'authorName' => $t->author_name,
                'authorRole' => $t->author_role,
                'photoUrl'   => $t->photo?->url,
            ]),

            'gallery' => GalleryImage::ordered()->with('image')->get()->map(fn ($g) => [
                'id'  => $g->id,
                'url' => $g->image?->url,
            ]),
        ]);
    }
}
