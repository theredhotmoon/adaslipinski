<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Illuminate\Http\{JsonResponse, Request};

class MilestoneController extends Controller
{
    public function index(): JsonResponse { return response()->json(Milestone::ordered()->get()); }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'year'       => 'required|string|max:4',
            'label'      => 'required|string|max:250',
            'sort_order' => 'sometimes|integer',
        ]);
        return response()->json(Milestone::create($data), 201);
    }

    public function show(Milestone $milestone): JsonResponse { return response()->json($milestone); }

    public function update(Request $request, Milestone $milestone): JsonResponse {
        $data = $request->validate([
            'year'       => 'sometimes|string|max:4',
            'label'      => 'sometimes|string|max:250',
            'sort_order' => 'sometimes|integer',
        ]);
        $milestone->update($data);
        return response()->json($milestone);
    }

    public function destroy(Milestone $milestone): JsonResponse {
        $milestone->delete();
        return response()->json(null, 204);
    }
}
