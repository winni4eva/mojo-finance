<?php

namespace App\Models;

use App\Models\Pipelines\AccountCreatingPipeline;
use App\Traits\AmountTrait;
use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, AmountTrait, FilterTrait, SoftDeletes;

    protected $fillable = ['user_id', 'amount', 'account_type_id', 'status'];

    protected $dispatchesEvents = [
        'creating' => AccountCreatingPipeline::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scheduledTransactions()
    {
        return $this->hasMany(ScheduledTransaction::class);
    }
}
