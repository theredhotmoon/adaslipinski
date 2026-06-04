<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\YearSummary;
use Illuminate\Http\{JsonResponse, Request};

class YearSummaryController extends Controller
{
    public function index(): JsonResponse { return response()->json(YearSummary::orderByDesc('year')->get()); }

    public function upsert(Request $request, int $year): JsonResponse {
        $data = $request->validate([
            'received_pln' => 'sometimes|integer|min:0',
            'spent_pln'    => 'sometimes|integer|min:0',
            'balance_pln'  => 'sometimes|integer',
            'tax_1_5_pln'  => 'sometimes|integer|min:0',
        ]);
        $summary = YearSummary::updateOrCreate(['year' => $year], $data);
        return response()->json($summary);
    }

    public function destroy(int $year): JsonResponse {
        YearSummary::where('year', $year)->delete();
        return response()->json(null, 204);
    }
}
