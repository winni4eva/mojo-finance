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
            'id' => (string)$this->id,
            'attributes' => [
                'amount' => $this->amount,
                'credit_account' => $this->credit_account_id,
                'debit_account' => $this->debit_account_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],
            'relationships' => [
                'debit_user_account' => $this->debitAccount->user->first_name .' '. $this->debitAccount->user->other_name.' '. $this->debitAccount->user->last_name,
                'credit_user_account' => $this->creditAccount->user->first_name .' '. $this->creditAccount->user->other_name.' '. $this->creditAccount->user->last_name
            ]
        ];
    }
}
