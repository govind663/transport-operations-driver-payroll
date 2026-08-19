<?php

namespace App\Http\Requests\Backend\Expense;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
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
            | Expense Code
            |--------------------------------------------------------------------------
            */

            'expense_code' => [
                'required',
                'string',
                'max:100',
                'unique:expenses,expense_code',
            ],


            /*
            |--------------------------------------------------------------------------
            | Expense Name
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
            | Expense Type
            |--------------------------------------------------------------------------
            */

            'expense_type' => [
                'required',
                Rule::in([
                    'fuel',
                    'toll',
                    'parking',
                    'food',
                    'maintenance',
                    'repair',
                    'miscellaneous',
                ]),
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
            | Expense Code
            |--------------------------------------------------------------------------
            */

            'expense_code.required' =>
                'Expense code is required.',

            'expense_code.string' =>
                'Expense code must be a valid text.',

            'expense_code.max' =>
                'Expense code may not exceed 100 characters.',

            'expense_code.unique' =>
                'This expense code already exists.',


            /*
            |--------------------------------------------------------------------------
            | Expense Name
            |--------------------------------------------------------------------------
            */

            'name.required' =>
                'Expense name is required.',

            'name.string' =>
                'Expense name must be a valid text.',

            'name.max' =>
                'Expense name may not exceed 150 characters.',


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
            | Expense Type
            |--------------------------------------------------------------------------
            */

            'expense_type.required' =>
                'Expense type is required.',

            'expense_type.in' =>
                'Selected expense type is invalid.',


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount.required' =>
                'Expense amount is required.',

            'amount.numeric' =>
                'Amount must be a valid number.',

            'amount.min' =>
                'Amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Expense status is required.',

            'status.boolean' =>
                'Invalid expense status selected.',
        ];
    }
}