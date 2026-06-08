<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Artisan;

/**
 * Bootstraps Passport so tests that issue real access tokens (e.g. the login
 * endpoint calling `createToken()`) work. Reuses on-disk keys when present
 * (local dev) and generates them only when missing (CI), so it never clobbers a
 * developer's existing keys. Creates a personal-access client in the test DB.
 */
trait SetsUpPassport
{
    protected function setUpPassport(): void
    {
        if (! file_exists(storage_path('oauth-private.key'))) {
            Artisan::call('passport:keys', ['--no-interaction' => true]);
        }

        Artisan::call('passport:client', [
            '--personal'       => true,
            '--name'           => 'Test Personal Access Client',
            '--no-interaction' => true,
        ]);
    }
}
