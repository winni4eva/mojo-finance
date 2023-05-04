<?php

namespace App\Http\Requests;

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
            'amount' => 'required|numeric|min:1',
            'account_type_id' => [
                'required',
                Rule::exists('accounts', 'id'),
                Rule::unique('accounts')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id)
                                ->where('account_type_id', $this->account_type_id);
                }),
            ],
        ];
    }

    public function messages()
{
    return [
        'required' => 'The :attribute field is required.',
        '*' => 'The :attribute field is invalid.',
    ];
}
}
