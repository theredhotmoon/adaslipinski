<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\{JsonResponse, Request};

class ExpenseController extends Controller
{
    public function index(): JsonResponse { return response()->json(Expense::orderByDesc('expense_date')->get()); }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'expense_date' => 'required|date',
            'description'  => 'required|string|max:300',
            'amount_pln'   => 'required|integer|min:0',
            'vendor'       => 'nullable|string|max:200',
            'invoice_url'  => 'nullable|string|max:500',
            'has_invoice'  => 'sometimes|boolean',
        ]);
        return response()->json(Expense::create($data), 201);
    }

    public function show(Expense $expense): JsonResponse { return response()->json($expense); }

    public function update(Request $request, Expense $expense): JsonResponse {
        $data = $request->validate([
            'expense_date' => 'sometimes|date',
            'description'  => 'sometimes|string|max:300',
            'amount_pln'   => 'sometimes|integer|min:0',
            'vendor'       => 'sometimes|nullable|string|max:200',
            'invoice_url'  => 'sometimes|nullable|string|max:500',
            'has_invoice'  => 'sometimes|boolean',
        ]);
        $expense->update($data);
        return response()->json($expense);
    }

    public function destroy(Expense $expense): JsonResponse {
        $expense->delete();
        return response()->json(null, 204);
    }
}
