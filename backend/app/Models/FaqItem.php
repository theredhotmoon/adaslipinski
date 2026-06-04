<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model {
    protected $fillable = ['question', 'answer', 'sort_order', 'active'];
    protected $casts = ['sort_order' => 'integer', 'active' => 'boolean'];

    public function scopeActive($query) { return $query->where('active', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
