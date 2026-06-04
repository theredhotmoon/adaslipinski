<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model {
    protected $fillable = ['year', 'label', 'sort_order'];
    protected $casts = ['sort_order' => 'integer'];

    public function scopeOrdered($query) { return $query->orderBy('sort_order')->orderBy('year'); }
}
