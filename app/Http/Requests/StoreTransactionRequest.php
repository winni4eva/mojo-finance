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
            'amount' => 'regex:/^\d+(\.\d{1,2})?$/',
            'credit_account' => 'required|numeric|min:1',
            'period' => [
                Rule::when(
                    request()->has('schedule') && request()->schedule,
                    ['required', 'date_format:Y-m-d H:i:s']
                ),
            ],
        ];
    }

    public function creditAccount(): Account
    {
        return Account::find($this->credit_account);
    }

    public function isTransactionScheduled(): bool
    {
        return request()->has('schedule') && request()->schedule;
    }

    public function hasEnoughBalance(): bool
    {
        if ((request()->amount / 100) > $this->account->amount) {
            return false;
        }
        return true;
    }

    public function messages()
    {
        return [
            'amount.regex' => 'The amount must be a valid currency value.',
        ];
    }
}
