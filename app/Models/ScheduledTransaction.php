<?php

namespace App\Models;

use App\Traits\AmountTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledTransaction extends Model
{
    use HasFactory, AmountTrait;

    protected $fillable = ['credit_account_id', 'debit_account_id', 'amount', 'user_id', 'period', 'time'];
}
