<?php
 
namespace App\Models\Events;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
 
class SetAccountNumberUuid
{
    public function __construct(Model $model)
    {
        $model->uuid = Str::uuid();
    }
}