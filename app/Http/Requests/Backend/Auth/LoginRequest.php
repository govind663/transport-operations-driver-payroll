<?php

namespace App\Http\Requests\Backend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
     */
    public function rules(): array
    {
        return [

            'username' => [
                'required',
                'string',
                'exists:users,username',
            ],

            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],

        ];
    }

    public function messages()
    {
        return [

            'username.required' => __('Username is required'),
            'username.exists' => __('Username does not exist'),
            'username.string' => __('Username must be a string'),

            'password.required' => __('Password is required'),
            'password.min' => __('Password must be at least 8 characters'),
            'password.mixedCase' => __('Password must contain both uppercase and lowercase letters'),
            'password.letters' => __('Password must contain letters'),
            'password.numbers' => __('Password must contain numbers'),
            'password.symbols' => __('Password must contain symbols'),

        ];
    }
}