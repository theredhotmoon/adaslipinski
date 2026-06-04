<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model {
    protected $fillable = ['key', 'file_path', 'disk', 'mime_type', 'size', 'alt_text', 'width', 'height'];
    protected $appends = ['url'];

    public function getUrlAttribute(): string {
        return Storage::disk($this->disk)->url($this->file_path);
    }
}
