<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores a translatable text field as a JSON map of locale => string, e.g.
 *   {"pl": "Fizjoterapia", "en": "Physiotherapy"}
 *
 * Reading returns the string for the current app locale (set per-request by the
 * SetLocale middleware), falling back to Polish (the original content language)
 * and then to any available translation.
 *
 * The cast is intentionally *tolerant*: legacy rows that still hold a plain
 * (non-JSON) string are returned as-is and treated as the fallback language, so
 * no data migration is required — a row is upgraded to JSON the first time it is
 * saved. Writing a plain string updates only the current locale and preserves
 * the others; writing an array ({"pl": .., "en": ..}) replaces the whole map.
 */
class Translatable implements CastsAttributes
{
    private const CONTENT_FALLBACK = 'pl';

    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $map = $this->decodeMap($value);
        if ($map === null) {
            return $value; // legacy plain string
        }

        $locale = app()->getLocale();

        return $map[$locale]
            ?? $map[self::CONTENT_FALLBACK]
            ?? $this->firstNonEmpty($map);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        // Full locale map provided — store as-is.
        if (is_array($value)) {
            return [$key => json_encode($value, JSON_UNESCAPED_UNICODE)];
        }

        // Plain string — merge into the existing map under the current locale.
        $existing = $this->decodeMap($model->getRawOriginal($key)) ?? [];
        if ($existing === [] && $model->getRawOriginal($key) !== null) {
            $existing = [self::CONTENT_FALLBACK => $model->getRawOriginal($key)];
        }
        $existing[app()->getLocale()] = $value;

        return [$key => json_encode($existing, JSON_UNESCAPED_UNICODE)];
    }

    /** Decode a stored value into a locale=>string map, or null if it isn't one. */
    private function decodeMap(?string $value): ?array
    {
        if ($value === null) {
            return null;
        }
        $decoded = json_decode($value, true);

        // Only treat associative objects (e.g. {"pl": ..}) as translation maps —
        // not plain strings, numbers, or JSON lists.
        return is_array($decoded) && ! array_is_list($decoded) ? $decoded : null;
    }

    private function firstNonEmpty(array $map): ?string
    {
        foreach ($map as $v) {
            if (is_string($v) && $v !== '') {
                return $v;
            }
        }
        return null;
    }
}
