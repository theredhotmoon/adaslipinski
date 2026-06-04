<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class YearSummary extends Model {
    protected $primaryKey = 'year';
    public $incrementing = false;
    protected $fillable = ['year', 'received_pln', 'spent_pln', 'balance_pln', 'tax_1_5_pln'];
    protected $casts = ['year' => 'integer', 'received_pln' => 'integer', 'spent_pln' => 'integer', 'balance_pln' => 'integer', 'tax_1_5_pln' => 'integer'];
}
