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

    protected $fillable = ['account_id', 'amount', 'type'];

    protected $dispatchesEvents = [
        'created' => NewTransactionCreated::class,
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
