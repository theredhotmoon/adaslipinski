<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Pings the static site's deploy hook so the Astro public site (web/) rebuilds with
 * the latest CMS content. Queued so a CMS save never waits on an outbound HTTP call.
 *
 * Requires a queue worker (`php artisan queue:work`) to run the delayed job. If the
 * deploy hook URL is not configured, this is a complete no-op.
 */
class TriggerSiteRebuild implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** Cache flag used to coalesce a burst of edits into a single rebuild. */
    public const PENDING_KEY = 'site-rebuild:pending';

    public int $tries = 3;
    public int $backoff = 30;

    /**
     * Schedule a (debounced) rebuild. Called from the CMS content observer. The first
     * change in the debounce window schedules the job; later changes are ignored until
     * the job runs and clears the flag.
     */
    public static function schedule(): void
    {
        $url = config('services.deploy.hook_url');
        if (blank($url)) {
            return; // integration disabled — do nothing
        }

        $debounce = max(0, (int) config('services.deploy.debounce', 60));

        // Cache::add is atomic: it returns false if the key already exists, so only the
        // first edit in the window wins and dispatches the job.
        if (! Cache::add(self::PENDING_KEY, true, $debounce + 10)) {
            return;
        }

        self::dispatch()->delay(now()->addSeconds($debounce));
    }

    public function handle(): void
    {
        Cache::forget(self::PENDING_KEY);

        $url = config('services.deploy.hook_url');
        if (blank($url)) {
            return;
        }

        $method = strtoupper((string) config('services.deploy.hook_method', 'POST'));

        $response = Http::timeout(15)->send($method, $url);

        if ($response->failed()) {
            Log::warning('Site rebuild hook returned an error', [
                'status' => $response->status(),
            ]);
        }
    }
}
