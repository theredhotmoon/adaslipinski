<?php
namespace App\Models;
use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model {
    use HasFactory;

    protected $fillable = ['question', 'answer', 'sort_order', 'active'];
    protected $casts = [
        'sort_order' => 'integer', 'active' => 'boolean',
        'question' => Translatable::class, 'answer' => Translatable::class,
    ];

    public function scopeActive($query) { return $query->where('active', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
