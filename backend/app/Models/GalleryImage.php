<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model {
    use HasFactory;
    protected $fillable = ['media_id', 'sort_order'];
    protected $casts = ['sort_order' => 'integer'];

    public function image() { return $this->belongsTo(Media::class, 'media_id'); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order')->orderBy('id'); }
}
