<?php

namespace App\Support;

use App\Models\SiteConfig;

/**
 * Admin-controlled site settings, stored as rows in `site_configs`:
 *   - `layout`          → which desktop layout the SPA renders (single active one)
 *   - `hidden_sections` → JSON array of public sections hidden on the Astro site
 *
 * Reads are tolerant: unknown/invalid values fall back to safe defaults, so a bad
 * row can never break rendering. Exposed publicly via /cms/site and written via the
 * admin SettingsController.
 */
class SiteSettings
{
    /** Desktop layouts the SPA can render. */
    public const LAYOUTS = ['classic', 'editorial', 'dashboard'];

    public const DEFAULT_LAYOUT = 'classic';

    /** Public sections an admin may hide on the Astro site (also the render order). */
    public const SECTIONS = ['budget', 'progress', 'expenses', 'tax', 'foundation', 'partners', 'faq'];

    public static function layout(): string
    {
        $value = SiteConfig::get('layout');

        return in_array($value, self::LAYOUTS, true) ? $value : self::DEFAULT_LAYOUT;
    }

    /** @return list<string> */
    public static function hiddenSections(): array
    {
        $raw = SiteConfig::get('hidden_sections');
        $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

        if (! is_array($decoded)) {
            return [];
        }

        // Keep only known section keys, deduped, in canonical order.
        return array_values(array_intersect(self::SECTIONS, $decoded));
    }

    /** @return array{layout: string, hiddenSections: list<string>} */
    public static function current(): array
    {
        return [
            'layout' => self::layout(),
            'hiddenSections' => self::hiddenSections(),
        ];
    }
}
