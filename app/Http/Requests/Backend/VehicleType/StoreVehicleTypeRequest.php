<?php

namespace App\Http\Requests\Backend\VehicleType;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleTypeRequest extends FormRequest
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
            | Vehicle Category
            |--------------------------------------------------------------------------
            */

            'vehicle_category_id' => [
                'required',
                'integer',
                'exists:vehicle_categories,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Vehicle Type Name
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Vehicle Type Code
            |--------------------------------------------------------------------------
            */

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:vehicle_types,code',
            ],

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description' => [
                'nullable',
                'string',
                'max:1000',
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


    /*
    |--------------------------------------------------------------------------
    | Custom Attribute Names
    |--------------------------------------------------------------------------
    */

    public function attributes(): array
    {
        return [

            'vehicle_category_id' =>
                'vehicle category',

            'name' =>
                'vehicle type name',

            'code' =>
                'vehicle type code',

            'description' =>
                'description',

            'status' =>
                'status',

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
            | Vehicle Category
            |--------------------------------------------------------------------------
            */

            'vehicle_category_id.required' =>
                'Please select a vehicle category.',

            'vehicle_category_id.integer' =>
                'Selected vehicle category is invalid.',

            'vehicle_category_id.exists' =>
                'Selected vehicle category does not exist.',


            /*
            |--------------------------------------------------------------------------
            | Vehicle Type Name
            |--------------------------------------------------------------------------
            */

            'name.required' =>
                'Vehicle type name is required.',

            'name.string' =>
                'Vehicle type name must be a valid text.',

            'name.max' =>
                'Vehicle type name may not exceed 100 characters.',


            /*
            |--------------------------------------------------------------------------
            | Vehicle Type Code
            |--------------------------------------------------------------------------
            */

            'code.required' =>
                'Vehicle type code is required.',

            'code.string' =>
                'Vehicle type code must be a valid text.',

            'code.max' =>
                'Vehicle type code may not exceed 50 characters.',

            'code.unique' =>
                'This vehicle type code already exists.',


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description.string' =>
                'Description must be a valid text.',

            'description.max' =>
                'Description may not exceed 1000 characters.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Please select the vehicle type status.',

            'status.boolean' =>
                'Vehicle type status must be active or inactive.',

        ];
    }
}