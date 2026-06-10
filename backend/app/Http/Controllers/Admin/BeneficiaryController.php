<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Illuminate\Http\{JsonResponse, Request};

class BeneficiaryController extends Controller
{
    public function show(): JsonResponse {
        return response()->json(Beneficiary::firstOrNew([]));
    }

    public function update(Request $request): JsonResponse {
        $data = $request->validate([
            'name'             => 'sometimes|string|max:100',
            'full_name'        => 'sometimes|string|max:150',
            'age'              => 'sometimes|nullable|integer|min:0|max:30',
            'diagnosis'        => 'sometimes|nullable|string',
            'diagnosis_plain'  => 'sometimes|nullable|string',
            'hero_kicker'      => 'sometimes|nullable|string|max:200',
            'hero_title'       => 'sometimes|nullable|string|max:300',
            'hero_subtitle'    => 'sometimes|nullable|string',
            'cta_label'        => 'sometimes|nullable|string|max:100',
            'cta_bar_label'    => 'sometimes|nullable|string|max:100',
            'recurring_default' => 'sometimes|boolean',
            'nfz_monthly_pln'  => 'sometimes|integer|min:0',
            'hero_image_id'    => 'sometimes|nullable|exists:media,id',
        ]);
        $b = Beneficiary::firstOrNew([]);
        $b->fill($data)->save();
        return response()->json($b);
    }
}
