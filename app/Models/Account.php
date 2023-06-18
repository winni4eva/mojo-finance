<?php

namespace App\Models;

use App\Models\Pipelines\AccountCreatingPipeline;
use App\Traits\AmountTrait;
use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// enum AccountType {
//     case CURRENT = 1;
//     case SAVING = 2;

//     public function name()
//     {
//         return match($this) {
//             self::CURRENT => 'Current',
//             default => 'Savings'
//         };
//     }
// }

class Account extends Model
{
    use HasFactory, AmountTrait, FilterTrait;

    protected $fillable = ['user_id', 'amount', 'account_type_id'];

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
