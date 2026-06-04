<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // In the container the Passport keys live outside the (Windows) bind-mounted
        // storage dir, so league/oauth2-server sees correct 600/660 permissions
        // instead of the 777 that bind mounts report.
        if (is_dir('/var/www/passport-keys')) {
            Passport::loadKeysFrom('/var/www/passport-keys');
        }
    }
}