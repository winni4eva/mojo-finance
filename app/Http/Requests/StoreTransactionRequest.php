<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'period' => [
                Rule::when(
                    request()->has('schedule') && request()->get('schedule'),
                    ['required', 'date_format:Y-m-d H:i:s']
                ),
            ],
        ];
    }

    public function creditAccount()
    {
        return Account::find($this->credit_account);
    }
}
