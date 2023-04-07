<?php

namespace App\Models;

use App\Traits\AmountTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, AmountTrait;

    protected $fillable = ['user_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'debit_account_id', 'id');
    }
}
