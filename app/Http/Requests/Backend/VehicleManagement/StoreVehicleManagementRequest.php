<?php

namespace App\Http\Requests\Backend\VehicleManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleManagementRequest extends FormRequest
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
            | Vehicle Type
            |--------------------------------------------------------------------------
            */

            'vehicle_type_id' => [
                'required',
                'integer',
                'exists:vehicle_types,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Vehicle Number
            |--------------------------------------------------------------------------
            */

            'vehicle_number' => [
                'required',
                'string',
                'max:50',
                'unique:vehicle_management,vehicle_number',
            ],


            /*
            |--------------------------------------------------------------------------
            | Registration Number
            |--------------------------------------------------------------------------
            */

            'registration_number' => [
                'required',
                'string',
                'max:100',
                'unique:vehicle_management,registration_number',
            ],


            /*
            |--------------------------------------------------------------------------
            | Chassis Number
            |--------------------------------------------------------------------------
            */

            'chassis_number' => [
                'nullable',
                'string',
                'max:100',
                'unique:vehicle_management,chassis_number',
            ],


            /*
            |--------------------------------------------------------------------------
            | Engine Number
            |--------------------------------------------------------------------------
            */

            'engine_number' => [
                'nullable',
                'string',
                'max:100',
                'unique:vehicle_management,engine_number',
            ],


            /*
            |--------------------------------------------------------------------------
            | Manufacturer
            |--------------------------------------------------------------------------
            */

            'manufacturer' => [
                'nullable',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Model
            |--------------------------------------------------------------------------
            */

            'model' => [
                'nullable',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Manufacturing Year
            |--------------------------------------------------------------------------
            */

            'manufacturing_year' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . (date('Y') + 1),
            ],


            /*
            |--------------------------------------------------------------------------
            | Color
            |--------------------------------------------------------------------------
            */

            'color' => [
                'nullable',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            'capacity' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Capacity Unit
            |--------------------------------------------------------------------------
            */

            'capacity_unit' => [
                'nullable',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'maintenance',
                    'inactive',
                ]),
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

            'vehicle_category_id' =>
                'vehicle category',

            'vehicle_type_id' =>
                'vehicle type',

            'vehicle_number' =>
                'vehicle number',

            'registration_number' =>
                'registration number',

            'chassis_number' =>
                'chassis number',

            'engine_number' =>
                'engine number',

            'manufacturer' =>
                'manufacturer',

            'model' =>
                'vehicle model',

            'manufacturing_year' =>
                'manufacturing year',

            'color' =>
                'vehicle color',

            'capacity' =>
                'vehicle capacity',

            'capacity_unit' =>
                'capacity unit',

            'status' =>
                'vehicle status',

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
            | Vehicle Type
            |--------------------------------------------------------------------------
            */

            'vehicle_type_id.required' =>
                'Please select a vehicle type.',

            'vehicle_type_id.integer' =>
                'Selected vehicle type is invalid.',

            'vehicle_type_id.exists' =>
                'Selected vehicle type does not exist.',


            /*
            |--------------------------------------------------------------------------
            | Vehicle Number
            |--------------------------------------------------------------------------
            */

            'vehicle_number.required' =>
                'Vehicle number is required.',

            'vehicle_number.string' =>
                'Vehicle number must be a valid text.',

            'vehicle_number.max' =>
                'Vehicle number may not exceed 50 characters.',

            'vehicle_number.unique' =>
                'This vehicle number already exists.',


            /*
            |--------------------------------------------------------------------------
            | Registration Number
            |--------------------------------------------------------------------------
            */

            'registration_number.required' =>
                'Registration number is required.',

            'registration_number.string' =>
                'Registration number must be a valid text.',

            'registration_number.max' =>
                'Registration number may not exceed 100 characters.',

            'registration_number.unique' =>
                'This registration number already exists.',


            /*
            |--------------------------------------------------------------------------
            | Chassis Number
            |--------------------------------------------------------------------------
            */

            'chassis_number.string' =>
                'Chassis number must be a valid text.',

            'chassis_number.max' =>
                'Chassis number may not exceed 100 characters.',

            'chassis_number.unique' =>
                'This chassis number already exists.',


            /*
            |--------------------------------------------------------------------------
            | Engine Number
            |--------------------------------------------------------------------------
            */

            'engine_number.string' =>
                'Engine number must be a valid text.',

            'engine_number.max' =>
                'Engine number may not exceed 100 characters.',

            'engine_number.unique' =>
                'This engine number already exists.',


            /*
            |--------------------------------------------------------------------------
            | Manufacturer
            |--------------------------------------------------------------------------
            */

            'manufacturer.string' =>
                'Manufacturer must be a valid text.',

            'manufacturer.max' =>
                'Manufacturer may not exceed 100 characters.',


            /*
            |--------------------------------------------------------------------------
            | Model
            |--------------------------------------------------------------------------
            */

            'model.string' =>
                'Vehicle model must be a valid text.',

            'model.max' =>
                'Vehicle model may not exceed 100 characters.',


            /*
            |--------------------------------------------------------------------------
            | Manufacturing Year
            |--------------------------------------------------------------------------
            */

            'manufacturing_year.integer' =>
                'Manufacturing year must be a valid year.',

            'manufacturing_year.min' =>
                'Manufacturing year cannot be before 1900.',

            'manufacturing_year.max' =>
                'Manufacturing year cannot be in the future.',


            /*
            |--------------------------------------------------------------------------
            | Color
            |--------------------------------------------------------------------------
            */

            'color.string' =>
                'Vehicle color must be a valid text.',

            'color.max' =>
                'Vehicle color may not exceed 50 characters.',


            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            'capacity.numeric' =>
                'Vehicle capacity must be a valid number.',

            'capacity.min' =>
                'Vehicle capacity cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Capacity Unit
            |--------------------------------------------------------------------------
            */

            'capacity_unit.string' =>
                'Capacity unit must be a valid text.',

            'capacity_unit.max' =>
                'Capacity unit may not exceed 50 characters.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Please select the vehicle status.',

            'status.in' =>
                'Selected vehicle status is invalid.',


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