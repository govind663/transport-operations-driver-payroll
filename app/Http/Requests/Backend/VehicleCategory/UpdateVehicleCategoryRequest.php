<?php

namespace App\Http\Requests\Backend\VehicleCategory;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleCategoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicleCategory = $this->route('vehicle_category');

        $vehicleCategoryId = is_object($vehicleCategory)
            ? $vehicleCategory->id
            : $vehicleCategory;

        return [

            /*
            |--------------------------------------------------------------------------
            | Vehicle Category
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'vehicle_categories',
                    'name'
                )->ignore($vehicleCategoryId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Category Code
            |--------------------------------------------------------------------------
            */

            'code' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'vehicle_categories',
                    'code'
                )->ignore($vehicleCategoryId),
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

            'name' => 'vehicle category name',

            'code' => 'vehicle category code',

            'description' => 'description',

            'status' => 'status',

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

            'name.required' =>
                'Vehicle category name is required.',

            'name.string' =>
                'Vehicle category name must be a valid text.',

            'name.max' =>
                'Vehicle category name may not exceed 100 characters.',

            'name.unique' =>
                'This vehicle category name already exists.',


            'code.required' =>
                'Vehicle category code is required.',

            'code.string' =>
                'Vehicle category code must be a valid text.',

            'code.max' =>
                'Vehicle category code may not exceed 50 characters.',

            'code.unique' =>
                'This vehicle category code already exists.',


            'description.string' =>
                'Description must be a valid text.',

            'description.max' =>
                'Description may not exceed 1000 characters.',


            'status.required' =>
                'Please select the vehicle category status.',

            'status.boolean' =>
                'Vehicle category status must be active or inactive.',

        ];
    }
}