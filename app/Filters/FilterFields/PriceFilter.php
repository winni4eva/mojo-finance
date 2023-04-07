<?php

namespace App\Filters\FilterFields;

class PriceFilter
{
    public function __invoke($query, $date)
    {
        // Add where between price ranges
        // Add pagination
        logger('filtering pricee');
        $price = request()->query('price', 0.00);

        $query->when(request('price_operand') == '>', fn ($q) => $q->where('amount', '>', $price));

        $query->when(request('price_operand') == '<', fn ($q) => $q->where('amount', '<', $price));

        $query->when(! request('price_operand') || request('price_operand') == '=', fn ($q) => $q->where('amount', $price));

        return $query;
    }
}
