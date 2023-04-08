<?php

namespace App\Models;

use App\Events\NewTransactionCreated;
use App\Traits\AmountTrait;
use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, AmountTrait, FilterTrait;

    protected $fillable = ['credit_account_id', 'debit_account_id', 'amount', 'user_id'];

    protected $dispatchesEvents = [
        'created' => NewTransactionCreated::class,
    ];

    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'debit_account_id', 'id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(Account::class, 'credit_account_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
