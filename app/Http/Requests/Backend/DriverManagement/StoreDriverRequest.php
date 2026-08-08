<?php

namespace App\Http\Requests\Backend\DriverManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Driver;

class StoreDriverRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            'driver_code' => [
                'required',
                'string',
                'max:30',
                'unique:drivers,driver_code',
            ],

            'driver_type' => [
                'required',
                'string',
                Rule::in(Driver::DRIVER_TYPES),
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'father_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:20',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */
            'mobile' => [
                'required',
                'digits:10',
            ],

            'alternate_mobile' => [
                'nullable',
                'digits:10',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:drivers,email',
            ],

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */
            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:10',
            ],

            /*
            |--------------------------------------------------------------------------
            | Driving Licence
            |--------------------------------------------------------------------------
            */
            'license_number' => [
                'required',
                'string',
                'max:50',
                'unique:drivers,license_number',
            ],

            'license_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'license_issue_date' => [
                'nullable',
                'date',
            ],

            'license_expiry_date' => [
                'required',
                'date',
                'after_or_equal:license_issue_date',
            ],

            'license_issuing_authority' => [
                'nullable',
                'string',
                'max:150',
            ],

            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */
            'driver_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'driving_license_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'aadhar_number' => [
                'nullable',
                'digits:12',
            ],

            'aadhar_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'pan_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
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
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'driver_code.required' => 'Driver code is required.',
            'driver_code.unique' => 'This driver code already exists.',

            'driver_type.required' => 'Driver type is required.',
            'driver_type.in' => 'Invalid driver type.',

            'first_name.required' => 'First name is required.',

            'mobile.required' => 'Mobile number is required.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',

            'alternate_mobile.digits' =>
                'Alternate mobile number must be exactly 10 digits.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.unique' =>
                'This email address is already registered.',

            'license_number.required' =>
                'Driving licence number is required.',

            'license_number.unique' =>
                'This driving licence number already exists.',

            'license_expiry_date.required' =>
                'Driving licence expiry date is required.',

            'license_expiry_date.after_or_equal' =>
                'Licence expiry date must be after or equal to the issue date.',

            'driver_photo.image' =>
                'Please upload a valid driver photo.',

            'driver_photo.mimes' =>
                'Driver photo must be JPG, JPEG, PNG or WEBP.',

            'driver_photo.max' =>
                'Driver photo size must not exceed 2 MB.',

            'driving_license_document.mimes' =>
                'Driving licence document must be JPG, JPEG, PNG or PDF.',

            'driving_license_document.max' =>
                'Driving licence document size must not exceed 5 MB.',

            'aadhar_number.digits' =>
                'Aadhar number must be exactly 12 digits.',

            'aadhar_document.mimes' =>
                'Aadhar document must be JPG, JPEG, PNG or PDF.',

            'pan_document.mimes' =>
                'PAN document must be JPG, JPEG, PNG or PDF.',

            'status.required' =>
                'Please select driver status.',

            'status.boolean' =>
                'Invalid driver status selected.',
        ];
    }
}