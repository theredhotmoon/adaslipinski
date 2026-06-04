<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(): JsonResponse {
        return response()->json(Media::orderByDesc('created_at')->get());
    }

    public function store(Request $request): JsonResponse {
        $request->validate([
            'file'     => 'required|file|mimes:jpg,jpeg,png,webp,gif,svg|max:10240',
            'key'      => 'nullable|string|max:100|unique:media,key',
            'alt_text' => 'nullable|string|max:250',
        ]);

        $file = $request->file('file');
        $path = $file->store('cms', 'public');

        [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

        $media = Media::create([
            'key'       => $request->input('key'),
            'file_path' => $path,
            'disk'      => 'public',
            'mime_type' => $file->getMimeType(),
            'size'      => $file->getSize(),
            'alt_text'  => $request->input('alt_text'),
            'width'     => $width,
            'height'    => $height,
        ]);

        return response()->json($media, 201);
    }

    public function show(Media $medium): JsonResponse {
        return response()->json($medium);
    }

    public function update(Request $request, Media $medium): JsonResponse {
        $data = $request->validate([
            'key'      => 'sometimes|nullable|string|max:100|unique:media,key,' . $medium->id,
            'alt_text' => 'sometimes|nullable|string|max:250',
        ]);
        $medium->update($data);
        return response()->json($medium);
    }

    public function destroy(Media $medium): JsonResponse {
        Storage::disk($medium->disk)->delete($medium->file_path);
        $medium->delete();
        return response()->json(null, 204);
    }
}
