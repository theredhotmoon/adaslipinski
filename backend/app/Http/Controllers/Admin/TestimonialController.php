<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\{JsonResponse, Request};

class TestimonialController extends Controller
{
    public function index(): JsonResponse { return response()->json(Testimonial::with('photo')->get()); }

    public function store(Request $request): JsonResponse {
        $data = $request->validate([
            'quote_text'  => 'required|string',
            'author_name' => 'required|string|max:150',
            'author_role' => 'nullable|string|max:200',
            'photo_id'    => 'nullable|exists:media,id',
            'active'      => 'sometimes|boolean',
        ]);
        return response()->json(Testimonial::create($data), 201);
    }

    public function show(Testimonial $testimonial): JsonResponse { return response()->json($testimonial->load('photo')); }

    public function update(Request $request, Testimonial $testimonial): JsonResponse {
        $data = $request->validate([
            'quote_text'  => 'sometimes|string',
            'author_name' => 'sometimes|string|max:150',
            'author_role' => 'sometimes|nullable|string|max:200',
            'photo_id'    => 'sometimes|nullable|exists:media,id',
            'active'      => 'sometimes|boolean',
        ]);
        $testimonial->update($data);
        return response()->json($testimonial->load('photo'));
    }

    public function destroy(Testimonial $testimonial): JsonResponse {
        $testimonial->delete();
        return response()->json(null, 204);
    }
}
