<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model {
    protected $fillable = ['quote_text', 'author_name', 'author_role', 'photo_id', 'active'];
    protected $casts = ['active' => 'boolean'];

    public function photo() { return $this->belongsTo(Media::class, 'photo_id'); }
    public function scopeActive($query) { return $query->where('active', true); }
}
