<?php

namespace App\Http\Requests\Backend\DriverManagement;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'required',
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
                Rule::in(Driver::LICENSE_TYPES),
            ],

            'license_issue_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'license_expiry_date' => [
                'nullable',
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
            | Qualification
            |--------------------------------------------------------------------------
            |
            | Add More Qualification Records
            |
            */

            'qualifications' => [
                'nullable',
                'array',
            ],

            'qualifications.*' => [
                'nullable',
                'array',
            ],

            'qualifications.*.qualification' => [
                'nullable',
                'string',
                'max:150',
            ],

            'qualifications.*.institute' => [
                'nullable',
                'string',
                'max:200',
            ],

            'qualifications.*.passing_year' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . now()->year,
            ],

            'qualifications.*.grade' => [
                'nullable',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Qualification Documents
            |--------------------------------------------------------------------------
            */

            'qualification_documents' => [
                'nullable',
                'array',
            ],

            'qualification_documents.*' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | Driver Nominee
            |--------------------------------------------------------------------------
            |
            | Add More Nominee Records
            |
            */

            'nominees' => [
                'nullable',
                'array',
            ],

            'nominees.*' => [
                'nullable',
                'array',
            ],

            'nominees.*.name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'nominees.*.relationship' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nominees.*.date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'nominees.*.mobile' => [
                'nullable',
                'digits:10',
            ],

            'nominees.*.percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'nominees.*.address' => [
                'nullable',
                'string',
                'max:500',
            ],


            /*
            |--------------------------------------------------------------------------
            | Driver Bank Details
            |--------------------------------------------------------------------------
            */

            'bank_details' => [
                'nullable',
                'array',
            ],

            'bank_details.account_holder_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'bank_details.bank_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'bank_details.account_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'bank_details.ifsc_code' => [
                'nullable',
                'string',
                'max:11',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            ],

            'bank_details.branch_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'bank_details.account_type' => [
                'nullable',
                'string',
                Rule::in([
                    'savings',
                    'current',
                ]),
            ],

            'bank_details.upi_id' => [
                'nullable',
                'string',
                'max:150',
            ],


            /*
            |--------------------------------------------------------------------------
            | Driver / Identity Documents
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

            'driver_code.string' =>
                'Driver code must be a valid text value.',

            'driver_code.max' =>
                'Driver code must not exceed 30 characters.',

            'driver_code.unique' =>
                'This driver code already exists.',

            'driver_type.required' =>
                'Driver type is required.',

            'driver_type.string' =>
                'Driver type must be a valid text value.',

            'driver_type.in' =>
                'Invalid driver type.',

            'first_name.required' =>
                'First name is required.',

            'first_name.string' =>
                'First name must be a valid text value.',

            'first_name.max' =>
                'First name must not exceed 100 characters.',

            'last_name.string' =>
                'Last name must be a valid text value.',

            'last_name.max' =>
                'Last name must not exceed 100 characters.',

            'father_name.string' =>
                'Father name must be a valid text value.',

            'father_name.max' =>
                'Father name must not exceed 150 characters.',

            'date_of_birth.date' =>
                'Please enter a valid date of birth.',

            'date_of_birth.before' =>
                'Date of birth must be a valid past date.',

            'gender.string' =>
                'Gender must be a valid value.',

            'gender.max' =>
                'Gender must not exceed 20 characters.',

            'marital_status.string' =>
                'Marital status must be a valid value.',

            'marital_status.max' =>
                'Marital status must not exceed 30 characters.',


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
                'Please select driver employment status.',

            'status.string' =>
                'Driver employment status must be a valid value.',

            'status.in' =>
                'Invalid driver employment status selected.',

            'resignation_date.date' =>
                'Please enter a valid resignation date.',

            'resignation_date.after_or_equal' =>
                'Resignation date cannot be before joining date.',

            'resignation_date.required_if' =>
                'Resignation date is required for notice period or resigned status.',

            'last_working_date.date' =>
                'Please enter a valid last working date.',

            'last_working_date.after_or_equal' =>
                'Last working date cannot be before joining date.',

            'last_working_date.required_if' =>
                'Last working date is required for resigned or terminated status.',

            'termination_date.date' =>
                'Please enter a valid termination date.',

            'termination_date.after_or_equal' =>
                'Termination date cannot be before joining date.',

            'termination_date.required_if' =>
                'Termination date is required for terminated status.',


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

            'email.max' =>
                'Email address must not exceed 255 characters.',

            'email.unique' =>
                'This email address is already registered.',


            /*
            |--------------------------------------------------------------------------
            | Address Information
            |--------------------------------------------------------------------------
            */

            'address.string' =>
                'Address must be a valid text value.',

            'city.string' =>
                'City must be a valid text value.',

            'city.max' =>
                'City must not exceed 100 characters.',

            'state.string' =>
                'State must be a valid text value.',

            'state.max' =>
                'State must not exceed 100 characters.',

            'country.string' =>
                'Country must be a valid text value.',

            'country.max' =>
                'Country must not exceed 100 characters.',

            'pincode.string' =>
                'Pincode must be a valid text value.',

            'pincode.max' =>
                'Pincode must not exceed 10 characters.',


            /*
            |--------------------------------------------------------------------------
            | Driving Licence
            |--------------------------------------------------------------------------
            */

            'license_number.required' =>
                'Driving licence number is required.',

            'license_number.string' =>
                'Driving licence number must be a valid text value.',

            'license_number.max' =>
                'Driving licence number must not exceed 50 characters.',

            'license_number.unique' =>
                'This driving licence number already exists.',

            'license_type.string' =>
                'Driving licence type must be a valid value.',

            'license_type.in' =>
                'Invalid driving licence type selected.',

            'license_issue_date.date' =>
                'Please enter a valid licence issue date.',

            'license_issue_date.before_or_equal' =>
                'Licence issue date cannot be a future date.',

            'license_expiry_date.required' =>
                'Driving licence expiry date is required.',

            'license_expiry_date.date' =>
                'Please enter a valid licence expiry date.',

            'license_expiry_date.after_or_equal' =>
                'Licence expiry date must be after or equal to the issue date.',

            'license_issuing_authority.string' =>
                'Licence issuing authority must be a valid text value.',

            'license_issuing_authority.max' =>
                'Licence issuing authority must not exceed 150 characters.',


            /*
            |--------------------------------------------------------------------------
            | Driver Qualifications
            |--------------------------------------------------------------------------
            */

            'qualifications.array' =>
                'Qualifications must be provided in a valid format.',

            'qualifications.*.array' =>
                'Each qualification record must be provided in a valid format.',

            'qualifications.*.qualification.string' =>
                'Qualification must be a valid text value.',

            'qualifications.*.qualification.max' =>
                'Qualification must not exceed 150 characters.',

            'qualifications.*.institute.string' =>
                'Institute / Board must be a valid text value.',

            'qualifications.*.institute.max' =>
                'Institute / Board must not exceed 200 characters.',

            'qualifications.*.passing_year.integer' =>
                'Passing year must be a valid year.',

            'qualifications.*.passing_year.min' =>
                'Passing year must be 1900 or later.',

            'qualifications.*.passing_year.max' =>
                'Passing year cannot be greater than the current year.',

            'qualifications.*.grade.string' =>
                'Percentage / Grade must be a valid text value.',

            'qualifications.*.grade.max' =>
                'Percentage / Grade must not exceed 50 characters.',


            /*
            |--------------------------------------------------------------------------
            | Qualification Documents
            |--------------------------------------------------------------------------
            */

            'qualification_documents.array' =>
                'Qualification documents must be provided in a valid format.',

            'qualification_documents.*.file' =>
                'Please upload a valid qualification document.',

            'qualification_documents.*.mimes' =>
                'Qualification document must be JPG, JPEG, PNG, WEBP or PDF.',

            'qualification_documents.*.max' =>
                'Each qualification document size must not exceed 5 MB.',


            /*
            |--------------------------------------------------------------------------
            | Driver Nominee
            |--------------------------------------------------------------------------
            */

            'nominees.array' =>
                'Nominees must be provided in a valid format.',

            'nominees.*.array' =>
                'Each nominee record must be provided in a valid format.',

            'nominees.*.name.required' =>
                'Nominee name is required.',

            'nominees.*.name.string' =>
                'Nominee name must be a valid text value.',

            'nominees.*.name.max' =>
                'Nominee name must not exceed 150 characters.',

            'nominees.*.relationship.required' =>
                'Nominee relationship is required.',

            'nominees.*.relationship.string' =>
                'Nominee relationship must be a valid text value.',

            'nominees.*.relationship.max' =>
                'Nominee relationship must not exceed 100 characters.',

            'nominees.*.date_of_birth.date' =>
                'Please enter a valid nominee date of birth.',

            'nominees.*.date_of_birth.before' =>
                'Nominee date of birth must be a valid past date.',

            'nominees.*.mobile.digits' =>
                'Nominee mobile number must be exactly 10 digits.',

            'nominees.*.percentage.numeric' =>
                'Nominee percentage must be a valid number.',

            'nominees.*.percentage.min' =>
                'Nominee percentage cannot be less than 0.',

            'nominees.*.percentage.max' =>
                'Nominee percentage cannot be greater than 100.',

            'nominees.*.address.string' =>
                'Nominee address must be a valid text value.',

            'nominees.*.address.max' =>
                'Nominee address must not exceed 500 characters.',


            /*
            |--------------------------------------------------------------------------
            | Driver Bank Details
            |--------------------------------------------------------------------------
            */

            'bank_details.array' =>
                'Bank details must be provided in a valid format.',

            'bank_details.account_holder_name.string' =>
                'Account holder name must be a valid text value.',

            'bank_details.account_holder_name.max' =>
                'Account holder name must not exceed 150 characters.',

            'bank_details.bank_name.string' =>
                'Bank name must be a valid text value.',

            'bank_details.bank_name.max' =>
                'Bank name must not exceed 150 characters.',

            'bank_details.account_number.string' =>
                'Account number must be a valid text value.',

            'bank_details.account_number.max' =>
                'Account number must not exceed 50 characters.',

            'bank_details.ifsc_code.string' =>
                'IFSC code must be a valid text value.',

            'bank_details.ifsc_code.max' =>
                'IFSC code must not exceed 11 characters.',

            'bank_details.ifsc_code.regex' =>
                'Please enter a valid IFSC code.',

            'bank_details.branch_name.string' =>
                'Branch name must be a valid text value.',

            'bank_details.branch_name.max' =>
                'Branch name must not exceed 150 characters.',

            'bank_details.account_type.string' =>
                'Account type must be a valid value.',

            'bank_details.account_type.in' =>
                'Invalid bank account type selected.',

            'bank_details.upi_id.string' =>
                'UPI ID must be a valid text value.',

            'bank_details.upi_id.max' =>
                'UPI ID must not exceed 150 characters.',


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

            'pan_number.string' =>
                'PAN number must be a valid text value.',

            'pan_number.max' =>
                'PAN number must not exceed 20 characters.',

            'pan_document.file' =>
                'Please upload a valid PAN document.',

            'pan_document.mimes' =>
                'PAN document must be JPG, JPEG, PNG or PDF.',

            'pan_document.max' =>
                'PAN document size must not exceed 5 MB.',
        ];
    }
}