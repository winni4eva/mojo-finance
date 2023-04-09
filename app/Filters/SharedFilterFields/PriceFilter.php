<?php

namespace App\Filters\SharedFilterFields;

class PriceFilter
{
    public function __invoke($query, $price)
    {
        // Add where between price ranges
        $price = $price ?? 0.00;

        $query->when(request('price_operand') == '>', fn ($q) => $q->where('amount', '>', $price));

        $query->when(request('price_operand') == '<', fn ($q) => $q->where('amount', '<', $price));

        $query->when(! request('price_operand') || request('price_operand') == '=', fn ($q) => $q->where('amount', $price));

        return $query;
    }
}
