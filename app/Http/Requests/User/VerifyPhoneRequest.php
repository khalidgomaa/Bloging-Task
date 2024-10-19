<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'phone' => 'required|string',
            'verification_code' => 'required|digits:6',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Phone number is required',
            'verification_code.required' => 'Verification code is required',
            'verification_code.digits' => 'Verification code must be exactly 6 digits',
        ];
    }

}
