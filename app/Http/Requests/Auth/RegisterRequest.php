<?php

namespace App\Http\Requests\Auth;

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
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    public function messages():array
    {
        return [
            'name.required'      => 'the name is required please!',
            'name.string'        => 'name should be string ',
            'name.max'           => 'name length should not be more than 255 !',
            'email.required'     => 'the email is required please!',
            'email.email'        => 'be sure you write email type',
            'email.unique'       => 'be sure the email is unique',
            'password.required'  => 'the password is required please!',
            'password.string'    => 'password should be string',
            'password.min'       => 'password should be at least 6 chars',
            'password.confirmed' => 'you have to confirm the password'
        ];
    }
}
