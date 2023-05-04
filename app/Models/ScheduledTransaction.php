<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'period' => 'date',
    ];

    protected $fillable = ['account_id', 'debit_account_id', 'amount', 'user_id', 'period'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
