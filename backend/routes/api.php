<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\SiteContentController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/health', [HealthController::class, 'check']);
Route::get('/cms/site', [SiteContentController::class, 'index']);

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

    // ── CMS Admin ─────────────────────────────────────────────────────────────
    Route::prefix('admin')->group(function () {

        // Beneficiary (single resource — show + update only)
        Route::get('/beneficiary', [Admin\BeneficiaryController::class, 'show']);
        Route::patch('/beneficiary', [Admin\BeneficiaryController::class, 'update']);

        // Foundation
        Route::get('/foundation', [Admin\FoundationController::class, 'show']);
        Route::patch('/foundation', [Admin\FoundationController::class, 'update']);
        Route::post('/foundation/{foundation}/accounts', [Admin\FoundationController::class, 'storeAccount']);
        Route::delete('/foundation/{foundation}/accounts/{account}', [Admin\FoundationController::class, 'destroyAccount']);
        Route::post('/foundation/{foundation}/links', [Admin\FoundationController::class, 'storeLink']);
        Route::delete('/foundation/{foundation}/links/{link}', [Admin\FoundationController::class, 'destroyLink']);

        // Full CRUD resources
        Route::apiResource('budget-items', Admin\BudgetItemController::class);
        // Route param names must match the controller's bound variable so implicit
        // model binding resolves (e.g. {progressPost} ↔ $progressPost). Without
        // this, faq/progress edits & deletes silently hit an empty model.
        Route::apiResource('progress', Admin\ProgressPostController::class)
             ->parameters(['progress' => 'progressPost']);
        Route::apiResource('milestones', Admin\MilestoneController::class);
        Route::apiResource('expenses', Admin\ExpenseController::class);
        Route::apiResource('faq', Admin\FaqItemController::class)
             ->parameters(['faq' => 'faqItem']);
        Route::apiResource('partners', Admin\PartnerController::class);
        Route::apiResource('donation-amounts', Admin\DonationAmountController::class)
             ->except(['show']);
        Route::apiResource('testimonials', Admin\TestimonialController::class);

        // Year summaries
        Route::get('/year-summaries', [Admin\YearSummaryController::class, 'index']);
        Route::put('/year-summaries/{year}', [Admin\YearSummaryController::class, 'upsert']);
        Route::delete('/year-summaries/{year}', [Admin\YearSummaryController::class, 'destroy']);

        // Gallery (About-page photo grid)
        Route::get('/gallery', [Admin\GalleryImageController::class, 'index']);
        Route::post('/gallery', [Admin\GalleryImageController::class, 'store']);
        Route::delete('/gallery/{galleryImage}', [Admin\GalleryImageController::class, 'destroy']);

        // Media
        Route::get('/media', [Admin\MediaController::class, 'index']);
        Route::post('/media', [Admin\MediaController::class, 'store']);
        Route::get('/media/{medium}', [Admin\MediaController::class, 'show']);
        Route::patch('/media/{medium}', [Admin\MediaController::class, 'update']);
        Route::delete('/media/{medium}', [Admin\MediaController::class, 'destroy']);

        // Site config (key-value)
        Route::get('/config', [Admin\SiteConfigController::class, 'index']);
        Route::put('/config/{key}', [Admin\SiteConfigController::class, 'upsert']);
        Route::delete('/config/{key}', [Admin\SiteConfigController::class, 'destroy']);
    });
});
