<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Foundation, FoundationAccount, FoundationLink};
use Illuminate\Http\{JsonResponse, Request};

class FoundationController extends Controller
{
    public function show(): JsonResponse {
        return response()->json(Foundation::with(['accounts', 'links'])->firstOrNew([]));
    }

    public function update(Request $request): JsonResponse {
        $data = $request->validate([
            'name'       => 'sometimes|string|max:250',
            'krs'        => 'sometimes|nullable|string|max:20',
            'nip'        => 'sometimes|nullable|string|max:20',
            'regon'      => 'sometimes|nullable|string|max:20',
            'cel'        => 'sometimes|nullable|string|max:150',
            'address'    => 'sometimes|nullable|string|max:300',
            'web'        => 'sometimes|nullable|string|max:200',
            'blik_phone' => 'sometimes|nullable|string|max:30',
            'email'      => 'sometimes|nullable|email|max:150',
            'phone'      => 'sometimes|nullable|string|max:30',
        ]);
        $f = Foundation::firstOrNew([]);
        $f->fill($data)->save();
        return response()->json($f->load(['accounts', 'links']));
    }

    public function storeAccount(Request $request, Foundation $foundation): JsonResponse {
        $data = $request->validate([
            'currency'   => 'required|string|size:3',
            'iban'       => 'required|string|max:60',
            'sort_order' => 'sometimes|integer',
        ]);
        return response()->json($foundation->accounts()->create($data), 201);
    }

    public function destroyAccount(Foundation $foundation, FoundationAccount $account): JsonResponse {
        abort_if($account->foundation_id !== $foundation->id, 404);
        $account->delete();
        return response()->json(null, 204);
    }

    public function storeLink(Request $request, Foundation $foundation): JsonResponse {
        $data = $request->validate([
            'label'      => 'required|string|max:150',
            'url'        => 'required|url|max:500',
            'sort_order' => 'sometimes|integer',
        ]);
        return response()->json($foundation->links()->create($data), 201);
    }

    public function destroyLink(Foundation $foundation, FoundationLink $link): JsonResponse {
        abort_if($link->foundation_id !== $foundation->id, 404);
        $link->delete();
        return response()->json(null, 204);
    }
}
