<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundationLink extends Model {
    use HasFactory;

    protected $fillable = ['foundation_id', 'label', 'url', 'sort_order'];
    protected $casts = ['sort_order' => 'integer'];

    public function foundation() { return $this->belongsTo(Foundation::class); }
}
