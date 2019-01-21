<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:6|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'description' => 'string|max:5000',
            'street' => 'string|max:255',
            'postcode' => 'string|max:255',
            'city' => 'string|max:255',
            'phone' => 'string|max:255',
            'date_of_birth' => 'date',
            'gender' => 'string|max:255',
            'new_password' => 'string|min:6|max:255|confirmed',
        ];
    }
}
