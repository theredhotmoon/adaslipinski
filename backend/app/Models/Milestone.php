<?php
namespace App\Models;
use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model {
    protected $fillable = ['year', 'label', 'sort_order'];
    protected $casts = ['sort_order' => 'integer', 'label' => Translatable::class];

    public function scopeOrdered($query) { return $query->orderBy('sort_order')->orderBy('year'); }
}
