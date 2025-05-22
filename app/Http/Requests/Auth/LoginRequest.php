<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
    public function rules(): array
    {
        return [
        'email'    => 'required|email',
        'password' => 'required|string'
        ];
    }

    public function messages():array
    {
        return [
            'email.required'     => 'the email is required please!',
            'email.email'        => 'be sure you write email type',
            'password.required'  => 'the password is required please!',
            'password.string'    => 'password should be string',
        ];
    }
}
