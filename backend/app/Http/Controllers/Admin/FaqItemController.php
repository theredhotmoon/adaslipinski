<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FaqItem;
use Illuminate\Http\{JsonResponse, Request};

class FaqItemController extends Controller
{
    public function index(): JsonResponse { return response()->json(FaqItem::ordered()->get()); }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'sometimes|integer',
            'active'     => 'sometimes|boolean',
        ]);
        return response()->json(FaqItem::create($data), 201);
    }

    public function show(FaqItem $faqItem): JsonResponse { return response()->json($faqItem); }

    public function update(Request $request, FaqItem $faqItem): JsonResponse {
        $data = $request->validate([
            'question'   => 'sometimes|string',
            'answer'     => 'sometimes|string',
            'sort_order' => 'sometimes|integer',
            'active'     => 'sometimes|boolean',
        ]);
        $faqItem->update($data);
        return response()->json($faqItem);
    }

    public function destroy(FaqItem $faqItem): JsonResponse {
        $faqItem->delete();
        return response()->json(null, 204);
    }
}
