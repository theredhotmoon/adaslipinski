<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProgressPost;
use Illuminate\Http\{JsonResponse, Request};

class ProgressPostController extends Controller
{
    public function index(): JsonResponse {
        return response()->json(ProgressPost::with('image')->orderByDesc('published_at')->get());
    }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'tag'          => 'nullable|string|max:80',
            'title'        => 'required|string|max:250',
            'body'         => 'required|string',
            'image_id'     => 'nullable|exists:media,id',
            'image_alt'    => 'nullable|string|max:250',
            'amount_pln'   => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ]);
        return response()->json(ProgressPost::create($data), 201);
    }

    public function show(ProgressPost $progressPost): JsonResponse {
        return response()->json($progressPost->load('image'));
    }

    public function update(Request $request, ProgressPost $progressPost): JsonResponse {
        $data = $request->validate([
            'tag'          => 'sometimes|nullable|string|max:80',
            'title'        => 'sometimes|string|max:250',
            'body'         => 'sometimes|string',
            'image_id'     => 'sometimes|nullable|exists:media,id',
            'image_alt'    => 'sometimes|nullable|string|max:250',
            'amount_pln'   => 'sometimes|nullable|integer|min:0',
            'published_at' => 'sometimes|nullable|date',
        ]);
        $progressPost->update($data);
        return response()->json($progressPost->load('image'));
    }

    public function destroy(ProgressPost $progressPost): JsonResponse {
        $progressPost->delete();
        return response()->json(null, 204);
    }
}
