<?php

namespace App\Filters\TransactionFilterFields;

class CreatedDateFilter
{
    public function __invoke($query, $date)
    {
        $query->when(request('date_operand') == '>', fn ($q) => $q->whereDate('created_at', '>', $date));

        $query->when(request('date_operand') == '<', fn ($q) => $q->whereDate('created_at', '<', $date));

        $query->when(!request('date_operand') || request('date_operand') == '=', fn ($q) => $q->whereDate('created_at', $date));
        
        return $query;
    }
}
