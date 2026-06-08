<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foundation extends Model {
    use HasFactory;

    protected $fillable = ['name', 'krs', 'nip', 'regon', 'cel', 'address', 'web', 'blik_phone', 'email', 'phone'];

    public function accounts() { return $this->hasMany(FoundationAccount::class)->orderBy('sort_order'); }
    public function links() { return $this->hasMany(FoundationLink::class)->orderBy('sort_order'); }
}
