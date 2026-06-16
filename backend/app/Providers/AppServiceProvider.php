<?php

namespace App\Providers;

use App\Models\{
    Beneficiary, BudgetItem, DonationAmount, Expense, FaqItem, Foundation,
    FoundationAccount, FoundationLink, GalleryImage, Media, Milestone, Partner,
    ProgressPost, SiteConfig, Testimonial, YearSummary
};
use App\Observers\CmsContentObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /** Models whose changes can alter the public /cms/site payload. */
    private const CMS_MODELS = [
        Beneficiary::class, BudgetItem::class, DonationAmount::class, Expense::class,
        FaqItem::class, Foundation::class, FoundationAccount::class, FoundationLink::class,
        GalleryImage::class, Media::class, Milestone::class, Partner::class,
        ProgressPost::class, SiteConfig::class, Testimonial::class, YearSummary::class,
    ];

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

        // Rebuild the static public site (web/) when published CMS content changes.
        foreach (self::CMS_MODELS as $model) {
            $model::observe(CmsContentObserver::class);
        }
    }
}