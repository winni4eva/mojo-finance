<?php

namespace App\Filters\TransactionFilterFields;

class PriceFilter
{
    public function __invoke($query, $date)
    {
        // Add where between price ranges
        // Add pagination
        $query->when(request('price_operand') == '>', fn ($q) => $q->where('amount', '>', $date));

        $query->when(request('price_operand') == '<', fn ($q) => $q->where('amount', '<', $date));

        $query->when(!request('price_operand') || request('price_operand') == '=', fn ($q) => $q->where('amount', $date));
        
        return $query;
    }
}
