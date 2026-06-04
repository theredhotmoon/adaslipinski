<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model {
    protected $fillable = ['slug', 'name', 'icon', 'frequency', 'cost_pln', 'note', 'sort_order', 'active'];
    protected $casts = ['cost_pln' => 'integer', 'sort_order' => 'integer', 'active' => 'boolean'];

    public function scopeActive($query) { return $query->where('active', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
