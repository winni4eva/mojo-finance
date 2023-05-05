<?php

namespace App\Http\Requests;

use App\Rules\AccountUserTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|between:1,9999999999.99',
            'account_type' => [
                'required',
                'integer',
                Rule::exists('accounts', 'id'),
                new AccountUserTypeRule,
            ],
        ];
    }
}
