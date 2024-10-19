<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
        'phone' => [
                        'required',
                        'digits:11',
                        'regex:/^01[0-9]{9}$/',
                        'unique:users,phone',
                    ],
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'The name must be at least :min characters.',
            'phone.required' => 'Phone number is required',
             'phone.digits' => 'Phone number must be exactly 11 digits',
            'phone.regex' => 'Phone number must start with 01 and be followed by 9 digits',
            'phone.unique' => 'Phone number is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ];
    }
}