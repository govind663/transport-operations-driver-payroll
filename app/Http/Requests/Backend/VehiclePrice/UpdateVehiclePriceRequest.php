<?php

namespace App\Http\Requests\Backend\VehiclePrice;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehiclePriceRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    public function authorize(): bool
    {
        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

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
            | Price
            |--------------------------------------------------------------------------
            */

            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999999.99',
            ],


            /*
            |--------------------------------------------------------------------------
            | Effective Date
            |--------------------------------------------------------------------------
            */

            'effective_date' => [
                'nullable',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Custom Attribute Names
    |--------------------------------------------------------------------------
    */

    public function attributes(): array
    {
        return [

            'price' =>
                'vehicle price',

            'effective_date' =>
                'effective date',

            'remarks' =>
                'remarks',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            'price.required' =>
                'Vehicle price is required.',

            'price.numeric' =>
                'Vehicle price must be a valid number.',

            'price.min' =>
                'Vehicle price cannot be negative.',

            'price.max' =>
                'Vehicle price is too large.',


            /*
            |--------------------------------------------------------------------------
            | Effective Date
            |--------------------------------------------------------------------------
            */

            'effective_date.date' =>
                'Effective date must be a valid date.',


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks.string' =>
                'Remarks must be a valid text.',

            'remarks.max' =>
                'Remarks may not exceed 2000 characters.',

        ];
    }
}