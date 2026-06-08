<?php
namespace App\Models;
use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    use HasFactory;

    protected $fillable = ['expense_date', 'description', 'amount_pln', 'vendor', 'invoice_url', 'has_invoice'];
    protected $casts = [
        'expense_date' => 'date', 'amount_pln' => 'integer', 'has_invoice' => 'boolean',
        'description' => Translatable::class, 'vendor' => Translatable::class,
    ];
}
