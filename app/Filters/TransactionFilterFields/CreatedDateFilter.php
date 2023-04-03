<?php

namespace App\Filters\TransactionFilterFields;

class CreatedDateFilter
{
    public function __invoke($query, $date)
    {
        $query->when(request('operand') == '>', fn ($q) => $q->whereDate('created_at', '>', $date));

        $query->when(request('operand') == '<', fn ($q) => $q->whereDate('created_at', '<', $date));

        $query->when(!request('operand') || request('operand') == '=', fn ($q) => $q->whereDate('created_at', $date));
        
        return $query;
    }
}
