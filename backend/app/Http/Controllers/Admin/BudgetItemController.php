<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use Illuminate\Http\{JsonResponse, Request};

class BudgetItemController extends Controller
{
    public function index(): JsonResponse {
        return response()->json(BudgetItem::ordered()->get());
    }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'slug'      => 'required|string|unique:budget_items|max:50',
            'name'      => 'required|string|max:150',
            'icon'      => 'nullable|string|max:50',
            'frequency' => 'nullable|string|max:100',
            'cost_pln'  => 'required|integer|min:0',
            'note'      => 'nullable|string',
            'sort_order' => 'sometimes|integer',
            'active'    => 'sometimes|boolean',
        ]);
        return response()->json(BudgetItem::create($data), 201);
    }

    public function show(BudgetItem $budgetItem): JsonResponse {
        return response()->json($budgetItem);
    }

    public function update(Request $request, BudgetItem $budgetItem): JsonResponse {
        $data = $request->validate([
            'name'      => 'sometimes|string|max:150',
            'icon'      => 'sometimes|nullable|string|max:50',
            'frequency' => 'sometimes|nullable|string|max:100',
            'cost_pln'  => 'sometimes|integer|min:0',
            'note'      => 'sometimes|nullable|string',
            'sort_order' => 'sometimes|integer',
            'active'    => 'sometimes|boolean',
        ]);
        $budgetItem->update($data);
        return response()->json($budgetItem);
    }

    public function destroy(BudgetItem $budgetItem): JsonResponse {
        $budgetItem->delete();
        return response()->json(null, 204);
    }
}
