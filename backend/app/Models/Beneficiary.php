<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model {
    protected $fillable = [
        'name', 'full_name', 'age', 'diagnosis', 'diagnosis_plain',
        'hero_kicker', 'hero_title', 'hero_subtitle',
        'cta_label', 'cta_bar_label', 'recurring_default', 'nfz_monthly_pln',
    ];
    protected $casts = ['recurring_default' => 'boolean', 'age' => 'integer', 'nfz_monthly_pln' => 'integer'];
}
