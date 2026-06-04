<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DonationAmount;
use Illuminate\Http\{JsonResponse, Request};

class DonationAmountController extends Controller
{
    public function index(): JsonResponse { return response()->json(DonationAmount::ordered()->get()); }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'amount_pln' => 'required|integer|min:1',
            'sort_order' => 'sometimes|integer',
            'active'     => 'sometimes|boolean',
        ]);
        return response()->json(DonationAmount::create($data), 201);
    }

    public function update(Request $request, DonationAmount $donationAmount): JsonResponse {
        $data = $request->validate([
            'amount_pln' => 'sometimes|integer|min:1',
            'sort_order' => 'sometimes|integer',
            'active'     => 'sometimes|boolean',
        ]);
        $donationAmount->update($data);
        return response()->json($donationAmount);
    }

    public function destroy(DonationAmount $donationAmount): JsonResponse {
        $donationAmount->delete();
        return response()->json(null, 204);
    }
}
