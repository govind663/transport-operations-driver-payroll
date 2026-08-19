<?php

namespace App\Http\Requests\Backend\Allowance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAllowanceRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Allowance Code
            |--------------------------------------------------------------------------
            */

            'allowance_code' => [
                'required',
                'string',
                'max:100',
                'unique:allowances,allowance_code',
            ],


            /*
            |--------------------------------------------------------------------------
            | Allowance Name
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:150',
            ],


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Calculation Type
            |--------------------------------------------------------------------------
            */

            'calculation_type' => [
                'required',
                Rule::in([
                    'fixed',
                    'per_day',
                    'per_km',
                    'per_hour',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'boolean',
            ],
        ];
    }


    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Allowance Code
            |--------------------------------------------------------------------------
            */

            'allowance_code.required' =>
                'Allowance code is required.',

            'allowance_code.string' =>
                'Allowance code must be a valid text.',

            'allowance_code.max' =>
                'Allowance code may not exceed 100 characters.',

            'allowance_code.unique' =>
                'This allowance code already exists.',


            /*
            |--------------------------------------------------------------------------
            | Allowance Name
            |--------------------------------------------------------------------------
            */

            'name.required' =>
                'Allowance name is required.',

            'name.string' =>
                'Allowance name must be a valid text.',

            'name.max' =>
                'Allowance name may not exceed 150 characters.',


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description.string' =>
                'Description must be valid text.',

            'description.max' =>
                'Description may not exceed 2000 characters.',


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount.required' =>
                'Allowance amount is required.',

            'amount.numeric' =>
                'Amount must be a valid number.',

            'amount.min' =>
                'Amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Calculation Type
            |--------------------------------------------------------------------------
            */

            'calculation_type.required' =>
                'Calculation type is required.',

            'calculation_type.in' =>
                'Selected calculation type is invalid.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Allowance status is required.',

            'status.boolean' =>
                'Invalid allowance status selected.',
        ];
    }
}