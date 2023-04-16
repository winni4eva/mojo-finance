<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', [Transaction::class, $this->account, $this->creditAccount(), $this->amount]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1',
            'credit_account' => 'required|numeric|min:1',
            //'account' => 'required|numeric|min:1',
        /** TODO add conditional validation for scheduled transactions */
        ];
    }

    public function creditAccount()
    {
        return Account::find($this->credit_account);
    }
}
