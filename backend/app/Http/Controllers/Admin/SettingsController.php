<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use App\Support\SiteSettings;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /** Current settings plus the available choices, for the admin Site Settings panel. */
    public function show(): JsonResponse
    {
        return response()->json([
            ...SiteSettings::current(),
            'availableLayouts' => SiteSettings::LAYOUTS,
            'availableSections' => SiteSettings::SECTIONS,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'layout' => ['sometimes', 'string', Rule::in(SiteSettings::LAYOUTS)],
            'hiddenSections' => ['sometimes', 'array'],
            'hiddenSections.*' => ['string', Rule::in(SiteSettings::SECTIONS)],
        ]);

        if (array_key_exists('layout', $data)) {
            SiteConfig::set('layout', $data['layout']);
        }

        if (array_key_exists('hiddenSections', $data)) {
            SiteConfig::set('hidden_sections', json_encode(array_values(array_unique($data['hiddenSections']))));
        }

        return response()->json(SiteSettings::current());
    }
}
