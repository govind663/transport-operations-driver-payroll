<?php

namespace App\Http\Requests\Backend\Allowance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAllowanceRequest extends FormRequest
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
        /*
        |--------------------------------------------------------------------------
        | Current Allowance ID
        |--------------------------------------------------------------------------
        |
        | Route model binding se allowance object mil sakta hai.
        | Agar route parameter ID ke form mein hai, fallback bhi handle kiya
        | gaya hai.
        |--------------------------------------------------------------------------
        */

        $allowance = $this->route('allowance');

        $allowanceId = is_object($allowance)
            ? $allowance->id
            : $allowance;


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

                Rule::unique(
                    'allowances',
                    'allowance_code'
                )->ignore($allowanceId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Allowance Name
            |--------------------------------------------------------------------------
            */

            'allowance_name' => [
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
            | Calculation Type
            |--------------------------------------------------------------------------
            */

            'calculation_type' => [
                'required',
                Rule::in([
                    'fixed',
                    'percentage',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Percentage
            |--------------------------------------------------------------------------
            */

            'percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Taxable
            |--------------------------------------------------------------------------
            */

            'is_taxable' => [
                'required',
                'boolean',
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

            'allowance_name.required' =>
                'Allowance name is required.',

            'allowance_name.string' =>
                'Allowance name must be a valid text.',

            'allowance_name.max' =>
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
            | Calculation Type
            |--------------------------------------------------------------------------
            */

            'calculation_type.required' =>
                'Calculation type is required.',

            'calculation_type.in' =>
                'Selected calculation type is invalid.',


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount.numeric' =>
                'Amount must be a valid number.',

            'amount.min' =>
                'Amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Percentage
            |--------------------------------------------------------------------------
            */

            'percentage.numeric' =>
                'Percentage must be a valid number.',

            'percentage.min' =>
                'Percentage cannot be negative.',

            'percentage.max' =>
                'Percentage cannot be greater than 100.',


            /*
            |--------------------------------------------------------------------------
            | Taxable
            |--------------------------------------------------------------------------
            */

            'is_taxable.required' =>
                'Please select whether this allowance is taxable.',

            'is_taxable.boolean' =>
                'Invalid taxable status selected.',


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