<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\{JsonResponse, Request};

class PartnerController extends Controller
{
    public function index(): JsonResponse { return response()->json(Partner::ordered()->with('logo')->get()); }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'logo_id'    => 'nullable|exists:media,id',
            'url'        => 'nullable|url|max:300',
            'sort_order' => 'sometimes|integer',
            'active'     => 'sometimes|boolean',
        ]);
        return response()->json(Partner::create($data), 201);
    }

    public function show(Partner $partner): JsonResponse { return response()->json($partner->load('logo')); }

    public function update(Request $request, Partner $partner): JsonResponse {
        $data = $request->validate([
            'name'       => 'sometimes|string|max:150',
            'logo_id'    => 'sometimes|nullable|exists:media,id',
            'url'        => 'sometimes|nullable|url|max:300',
            'sort_order' => 'sometimes|integer',
            'active'     => 'sometimes|boolean',
        ]);
        $partner->update($data);
        return response()->json($partner->load('logo'));
    }

    public function destroy(Partner $partner): JsonResponse {
        $partner->delete();
        return response()->json(null, 204);
    }
}
