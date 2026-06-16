<?php

namespace Tests\Feature;

use App\Jobs\TriggerSiteRebuild;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SiteRebuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_rebuild_is_scheduled_when_hook_url_is_not_configured(): void
    {
        config(['services.deploy.hook_url' => null]);
        Queue::fake();

        Partner::factory()->create();

        Queue::assertNothingPushed();
    }

    public function test_a_cms_change_schedules_a_rebuild_when_configured(): void
    {
        config(['services.deploy.hook_url' => 'https://hook.test/build']);
        Cache::flush();
        Queue::fake();

        Partner::factory()->create();

        Queue::assertPushed(TriggerSiteRebuild::class, 1);
    }

    public function test_a_burst_of_changes_coalesces_into_one_rebuild(): void
    {
        config(['services.deploy.hook_url' => 'https://hook.test/build', 'services.deploy.debounce' => 60]);
        Cache::flush();
        Queue::fake();

        // Several edits in quick succession (Queue::fake means the job never runs to
        // clear the pending flag, so the debounce window stays "armed").
        Partner::factory()->create();
        Partner::factory()->create();
        $p = Partner::factory()->create();
        $p->delete();

        Queue::assertPushed(TriggerSiteRebuild::class, 1);
    }

    public function test_the_job_posts_to_the_deploy_hook(): void
    {
        config([
            'services.deploy.hook_url' => 'https://hook.test/build',
            'services.deploy.hook_method' => 'POST',
        ]);
        Http::fake();

        (new TriggerSiteRebuild())->handle();

        Http::assertSent(fn ($request) => $request->url() === 'https://hook.test/build' && $request->method() === 'POST');
    }
}
