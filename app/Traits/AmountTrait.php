<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait AmountTrait
{
    /**
     * Interact with amount.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
