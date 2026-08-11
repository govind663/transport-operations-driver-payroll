<?php

namespace App\Http\Requests\Backend\DriverManagement;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
        $driver = $this->route('driver');

        /*
        |--------------------------------------------------------------------------
        | Resolve Driver ID
        |--------------------------------------------------------------------------
        */

        $driverId = $driver instanceof Driver
            ? $driver->id
            : $driver;


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
                Rule::unique('drivers', 'driver_code')
                    ->ignore($driverId),
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
                'before:today',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:20',
            ],

            'marital_status' => [
                'nullable',
                'string',
                'max:30',
            ],


            /*
            |--------------------------------------------------------------------------
            | Employment Information
            |--------------------------------------------------------------------------
            */

            'joining_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'status' => [
                'required',
                'string',
                Rule::in(Driver::EMPLOYMENT_STATUSES),
            ],

            'resignation_date' => [
                'nullable',
                'date',
                'after_or_equal:joining_date',
                'required_if:status,notice_period,resigned',
            ],

            'last_working_date' => [
                'nullable',
                'date',
                'after_or_equal:joining_date',
                'required_if:status,resigned,terminated',
            ],

            'termination_date' => [
                'nullable',
                'date',
                'after_or_equal:joining_date',
                'required_if:status,terminated',
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
                'different:mobile',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('drivers', 'email')
                    ->ignore($driverId),
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
                Rule::unique('drivers', 'license_number')
                    ->ignore($driverId),
            ],

            'license_type' => [
                'nullable',
                'string',
                Rule::in(Driver::LICENSE_TYPES),
            ],

            'license_issue_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
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

        ];
    }


    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [
                        /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'driver_code.required' =>
                'Driver code is required.',

            'driver_code.unique' =>
                'This driver code already exists.',

            'driver_type.required' =>
                'Driver type is required.',

            'driver_type.in' =>
                'Invalid driver type.',

            'first_name.required' =>
                'First name is required.',

            'date_of_birth.before' =>
                'Date of birth must be a valid past date.',


            /*
            |--------------------------------------------------------------------------
            | Employment Information
            |--------------------------------------------------------------------------
            */

            'joining_date.required' =>
                'Joining date is required.',

            'joining_date.date' =>
                'Please enter a valid joining date.',

            'joining_date.before_or_equal' =>
                'Joining date cannot be a future date.',

            'status.required' =>
                'Please select driver status.',

            'status.in' =>
                'Invalid driver status selected.',

            'resignation_date.required_if' =>
                'Resignation date is required for notice period or resigned status.',

            'resignation_date.date' =>
                'Please enter a valid resignation date.',

            'resignation_date.after_or_equal' =>
                'Resignation date cannot be before joining date.',

            'last_working_date.required_if' =>
                'Last working date is required for resigned or terminated status.',

            'last_working_date.date' =>
                'Please enter a valid last working date.',

            'last_working_date.after_or_equal' =>
                'Last working date cannot be before joining date.',

            'termination_date.required_if' =>
                'Termination date is required for terminated status.',

            'termination_date.date' =>
                'Please enter a valid termination date.',

            'termination_date.after_or_equal' =>
                'Termination date cannot be before joining date.',


            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */

            'mobile.required' =>
                'Mobile number is required.',

            'mobile.digits' =>
                'Mobile number must be exactly 10 digits.',

            'alternate_mobile.digits' =>
                'Alternate mobile number must be exactly 10 digits.',

            'alternate_mobile.different' =>
                'Alternate mobile number must be different from mobile number.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.unique' =>
                'This email address is already registered.',


            /*
            |--------------------------------------------------------------------------
            | Driving Licence
            |--------------------------------------------------------------------------
            */

            'license_number.required' =>
                'Driving licence number is required.',

            'license_number.unique' =>
                'This driving licence number already exists.',

            'license_type.in' =>
                'Invalid driving licence type selected.',

            'license_issue_date.date' =>
                'Please enter a valid licence issue date.',

            'license_issue_date.before_or_equal' =>
                'Licence issue date cannot be a future date.',

            'license_expiry_date.required' =>
                'Driving licence expiry date is required.',

            'license_expiry_date.after_or_equal' =>
                'Licence expiry date must be after or equal to the issue date.',


            /*
            |--------------------------------------------------------------------------
            | Driver Photo
            |--------------------------------------------------------------------------
            */

            'driver_photo.image' =>
                'Please upload a valid driver photo.',

            'driver_photo.mimes' =>
                'Driver photo must be JPG, JPEG, PNG or WEBP.',

            'driver_photo.max' =>
                'Driver photo size must not exceed 2 MB.',


            /*
            |--------------------------------------------------------------------------
            | Driving Licence Document
            |--------------------------------------------------------------------------
            */

            'driving_license_document.file' =>
                'Please upload a valid driving licence document.',

            'driving_license_document.mimes' =>
                'Driving licence document must be JPG, JPEG, PNG or PDF.',

            'driving_license_document.max' =>
                'Driving licence document size must not exceed 5 MB.',


            /*
            |--------------------------------------------------------------------------
            | Aadhaar
            |--------------------------------------------------------------------------
            */

            'aadhar_number.digits' =>
                'Aadhaar number must be exactly 12 digits.',

            'aadhar_document.file' =>
                'Please upload a valid Aadhaar document.',

            'aadhar_document.mimes' =>
                'Aadhaar document must be JPG, JPEG, PNG or PDF.',

            'aadhar_document.max' =>
                'Aadhaar document size must not exceed 5 MB.',


            /*
            |--------------------------------------------------------------------------
            | PAN
            |--------------------------------------------------------------------------
            */

            'pan_document.file' =>
                'Please upload a valid PAN document.',

            'pan_document.mimes' =>
                'PAN document must be JPG, JPEG, PNG or PDF.',

            'pan_document.max' =>
                'PAN document size must not exceed 5 MB.',
        ];
    }
}