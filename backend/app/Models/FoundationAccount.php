<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FoundationAccount extends Model {
    protected $fillable = ['foundation_id', 'currency', 'iban', 'sort_order'];
    protected $casts = ['sort_order' => 'integer'];

    public function foundation() { return $this->belongsTo(Foundation::class); }
}
