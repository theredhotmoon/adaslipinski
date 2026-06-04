<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProgressPost extends Model {
    protected $fillable = ['tag', 'title', 'body', 'image_id', 'image_alt', 'amount_pln', 'published_at'];
    protected $casts = ['amount_pln' => 'integer', 'published_at' => 'datetime'];

    public function image() { return $this->belongsTo(Media::class, 'image_id'); }

    public function scopePublished($query) {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
