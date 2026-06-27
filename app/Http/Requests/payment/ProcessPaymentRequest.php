<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'Please enter your full name.',
            'name.string' => 'The full name must be a valid string.',
            'name.max' => 'The full name may not exceed 255 characters.',

            // Email
            'email.required' => 'Please enter your email address.',
            'email.string' => 'The email address must be a valid string.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email address may not exceed 255 characters.',
            'email.unique' => 'This email address is already in use.',

            // Password
            'password.required' => 'Please enter a password.',
            'password.string' => 'The password must be a valid string.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters long.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
        ];
    }
}