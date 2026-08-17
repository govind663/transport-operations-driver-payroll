<?php

namespace App\Http\Requests\Backend\ClientManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'client_code' => [
                'required',
                'string',
                'max:30',
                'unique:clients,client_code',
            ],

            'category' => [
                'required',
                'in:RIL,OTHER',
            ],

            'company_name' => [
                'required',
                'string',
                'max:200',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:150',
            ],

            /*
            |--------------------------------------------------------------------------
            | Company Logo
            |--------------------------------------------------------------------------
            */
            'company_logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */
            'mobile' => [
                'nullable',
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
                'unique:clients,email',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | GST
            |--------------------------------------------------------------------------
            */
            'gst_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'max:20',
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
            | Billing Address
            |--------------------------------------------------------------------------
            */
            'billing_address' => [
                'nullable',
                'string',
            ],

            'billing_city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'billing_state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'billing_country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'billing_pincode' => [
                'nullable',
                'string',
                'max:10',
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
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            'client_code.required'        => 'Client code is required.',
            'client_code.unique'          => 'This client code already exists.',
            'client_code.max'             => 'Client code may not be greater than 30 characters.',

            'category.required'           => 'Client category is required.',
            'category.in'                 => 'Please select a valid client category.',

            'company_name.required'       => 'Company name is required.',
            'company_name.max'            => 'Company name may not be greater than 200 characters.',

            'contact_person.max'          => 'Contact person name may not be greater than 150 characters.',

            /*
            |--------------------------------------------------------------------------
            | Company Logo
            |--------------------------------------------------------------------------
            */
            'company_logo.image'          => 'Please upload a valid company logo.',
            'company_logo.mimes'          => 'Company logo must be a JPG, JPEG, PNG or WEBP file.',
            'company_logo.max'            => 'Company logo size must not exceed 2 MB.',

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */
            'mobile.digits'              => 'Mobile number must be exactly 10 digits.',
            'alternate_mobile.digits'    => 'Alternate mobile number must be exactly 10 digits.',

            'email.email'                => 'Please enter a valid email address.',
            'email.unique'               => 'This email address is already registered.',
            'email.max'                  => 'Email address may not be greater than 255 characters.',

            'website.url'                => 'Please enter a valid website URL.',

            /*
            |--------------------------------------------------------------------------
            | GST & PAN
            |--------------------------------------------------------------------------
            */
            'gst_number.max'             => 'GST number may not be greater than 20 characters.',
            'pan_number.max'             => 'PAN number may not be greater than 20 characters.',

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */
            'city.max'                   => 'City name may not be greater than 100 characters.',
            'state.max'                  => 'State name may not be greater than 100 characters.',
            'country.max'                => 'Country name may not be greater than 100 characters.',
            'pincode.max'                => 'Pincode may not be greater than 10 characters.',

            /*
            |--------------------------------------------------------------------------
            | Billing Address
            |--------------------------------------------------------------------------
            */
            'billing_city.max'           => 'Billing city may not be greater than 100 characters.',
            'billing_state.max'          => 'Billing state may not be greater than 100 characters.',
            'billing_country.max'        => 'Billing country may not be greater than 100 characters.',
            'billing_pincode.max'        => 'Billing pincode may not be greater than 10 characters.',

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            'status.required'            => 'Please select client status.',
            'status.boolean'             => 'Invalid client status selected.',

        ];
    }
}