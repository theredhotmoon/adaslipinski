<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model {
    protected $fillable = ['name', 'logo_id', 'url', 'sort_order', 'active'];
    protected $casts = ['sort_order' => 'integer', 'active' => 'boolean'];

    public function logo() { return $this->belongsTo(Media::class, 'logo_id'); }
    public function scopeActive($query) { return $query->where('active', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
