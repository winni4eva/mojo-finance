<?php

namespace App\Models;

use App\Enums\AccountType as EnumsAccountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => EnumsAccountType::class,
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
