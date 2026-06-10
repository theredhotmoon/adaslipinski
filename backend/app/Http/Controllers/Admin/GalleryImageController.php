<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\{JsonResponse, Request};

class GalleryImageController extends Controller
{
    public function index(): JsonResponse {
        return response()->json(GalleryImage::ordered()->with('image')->get());
    }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'media_id'   => 'required|exists:media,id',
            'sort_order' => 'sometimes|integer',
        ]);
        return response()->json(GalleryImage::create($data)->load('image'), 201);
    }

    public function destroy(GalleryImage $galleryImage): JsonResponse {
        $galleryImage->delete();
        return response()->json(null, 204);
    }
}
