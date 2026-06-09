<?php
namespace App\Models;
use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model {
    use HasFactory;

    protected $fillable = [
        'name', 'full_name', 'age', 'diagnosis', 'diagnosis_plain',
        'hero_kicker', 'hero_title', 'hero_subtitle',
        'cta_label', 'cta_bar_label', 'recurring_default', 'nfz_monthly_pln',
        'hero_image_id',
    ];

    public function heroImage() { return $this->belongsTo(Media::class, 'hero_image_id'); }
    protected $casts = [
        'recurring_default' => 'boolean', 'age' => 'integer', 'nfz_monthly_pln' => 'integer',
        'diagnosis' => Translatable::class, 'diagnosis_plain' => Translatable::class,
        'hero_kicker' => Translatable::class, 'hero_title' => Translatable::class,
        'hero_subtitle' => Translatable::class, 'cta_label' => Translatable::class,
        'cta_bar_label' => Translatable::class,
    ];
}
