<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use Illuminate\Http\{JsonResponse, Request};

class SiteConfigController extends Controller
{
    public function index(): JsonResponse {
        return response()->json(SiteConfig::all()->pluck('value', 'key'));
    }

    public function upsert(Request $request, string $key): JsonResponse {
        $request->validate(['value' => 'required']);
        SiteConfig::set($key, $request->input('value'));
        return response()->json(['key' => $key, 'value' => $request->input('value')]);
    }

    public function destroy(string $key): JsonResponse {
        SiteConfig::where('key', $key)->delete();
        return response()->json(null, 204);
    }
}
