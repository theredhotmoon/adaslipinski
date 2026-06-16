<?php

namespace App\Observers;

use App\Jobs\TriggerSiteRebuild;
use Illuminate\Database\Eloquent\Model;

/**
 * Watches the CMS content models. Any create/update/delete that could change what
 * /cms/site returns schedules a (debounced) rebuild of the static public site.
 *
 * Registered against every content model in AppServiceProvider. Seeding is exempt
 * because the seeder uses the WithoutModelEvents trait.
 */
class CmsContentObserver
{
    public function saved(Model $model): void
    {
        TriggerSiteRebuild::schedule();
    }

    public function deleted(Model $model): void
    {
        TriggerSiteRebuild::schedule();
    }
}
