<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'attributes' => [
                'amount' => $this->amount,
                'credit_account_id' => $this->credit_account_id,
                'debit_account_id' => $this->debit_account_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],
            'relationships' => [
                'debit_account' => new AccountResource($this->debitAccount),
                'credit_account' => new AccountResource($this->creditAccount)
            ]
        ];
    }
}
