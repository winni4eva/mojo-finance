<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['credit_account_id', 'debit_account_id', 'amount'];

    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'id', 'debit_account_id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(Account::class, 'id', 'credit_account_id');
    }
}
