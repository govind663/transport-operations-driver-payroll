<?php

namespace App\Http\Requests\Backend\TravelRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTravelRequestRequest extends FormRequest
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
        | Current Travel Request
        |--------------------------------------------------------------------------
        */

        $travelRequest = $this->route('travel_request');

        $travelRequestId = is_object($travelRequest)
            ? $travelRequest->id
            : $travelRequest;

        return [

            /*
            |--------------------------------------------------------------------------
            | Request Number
            |--------------------------------------------------------------------------
            */

            'request_no' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique(
                    'travel_requests',
                    'request_no'
                )->ignore($travelRequestId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Client
            |--------------------------------------------------------------------------
            */

            'client_id' => [
                'required',
                'integer',
                'exists:clients,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Requested By
            |--------------------------------------------------------------------------
            */

            'requested_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Passenger Name
            |--------------------------------------------------------------------------
            */

            'passenger_name' => [
                'required',
                'string',
                'max:150',
            ],

            /*
            |--------------------------------------------------------------------------
            | Passenger Phone
            |--------------------------------------------------------------------------
            */

            'passenger_phone' => [
                'required',
                'string',
                'max:20',
            ],

            /*
            |--------------------------------------------------------------------------
            | Pickup Location
            |--------------------------------------------------------------------------
            */

            'pickup_location' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Drop Location
            |--------------------------------------------------------------------------
            */

            'drop_location' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Travel Date & Time
            |--------------------------------------------------------------------------
            */

            'travel_date_time' => [
                'required',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Passenger Count
            |--------------------------------------------------------------------------
            */

            'passenger_count' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Purpose
            |--------------------------------------------------------------------------
            */

            'purpose' => [
                'nullable',
                'string',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'assigned',
                    'completed',
                    'cancelled',
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

    /**
     * Prepare request data before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([

            /*
            |--------------------------------------------------------------------------
            | Request Number
            |--------------------------------------------------------------------------
            */

            'request_no' => $this->filled('request_no')
                ? strtoupper(trim($this->request_no))
                : null,

            /*
            |--------------------------------------------------------------------------
            | Passenger Name
            |--------------------------------------------------------------------------
            */

            'passenger_name' => $this->filled('passenger_name')
                ? preg_replace(
                    '/\s+/',
                    ' ',
                    trim($this->passenger_name)
                )
                : null,

            /*
            |--------------------------------------------------------------------------
            | Passenger Phone
            |--------------------------------------------------------------------------
            */

            'passenger_phone' => $this->filled('passenger_phone')
                ? preg_replace(
                    '/[^0-9+]/',
                    '',
                    $this->passenger_phone
                )
                : null,

            /*
            |--------------------------------------------------------------------------
            | Pickup Location
            |--------------------------------------------------------------------------
            */

            'pickup_location' => $this->filled('pickup_location')
                ? preg_replace(
                    '/\s+/',
                    ' ',
                    trim($this->pickup_location)
                )
                : null,

            /*
            |--------------------------------------------------------------------------
            | Drop Location
            |--------------------------------------------------------------------------
            */

            'drop_location' => $this->filled('drop_location')
                ? preg_replace(
                    '/\s+/',
                    ' ',
                    trim($this->drop_location)
                )
                : null,

            /*
            |--------------------------------------------------------------------------
            | Purpose
            |--------------------------------------------------------------------------
            */

            'purpose' => $this->filled('purpose')
                ? preg_replace(
                    '/\s+/',
                    ' ',
                    trim($this->purpose)
                )
                : null,

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => $this->filled('remarks')
                ? trim($this->remarks)
                : null,
        ]);
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Request Number
            |--------------------------------------------------------------------------
            */

            'request_no.string' =>
                'Request number must be valid text.',

            'request_no.max' =>
                'Request number may not exceed 100 characters.',

            'request_no.unique' =>
                'This travel request number already exists.',

            /*
            |--------------------------------------------------------------------------
            | Client
            |--------------------------------------------------------------------------
            */

            'client_id.required' =>
                'Please select a client.',

            'client_id.integer' =>
                'Selected client is invalid.',

            'client_id.exists' =>
                'Selected client does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Requested By
            |--------------------------------------------------------------------------
            */

            'requested_by.integer' =>
                'Selected requester is invalid.',

            'requested_by.exists' =>
                'Selected requester does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Passenger
            |--------------------------------------------------------------------------
            */

            'passenger_name.required' =>
                'Passenger name is required.',

            'passenger_name.string' =>
                'Passenger name must be valid text.',

            'passenger_name.max' =>
                'Passenger name may not exceed 150 characters.',

            'passenger_phone.required' =>
                'Passenger phone number is required.',

            'passenger_phone.string' =>
                'Passenger phone number must be valid.',

            'passenger_phone.max' =>
                'Passenger phone number may not exceed 20 characters.',

            /*
            |--------------------------------------------------------------------------
            | Locations
            |--------------------------------------------------------------------------
            */

            'pickup_location.required' =>
                'Pickup location is required.',

            'pickup_location.string' =>
                'Pickup location must be valid text.',

            'pickup_location.max' =>
                'Pickup location may not exceed 255 characters.',

            'drop_location.required' =>
                'Drop location is required.',

            'drop_location.string' =>
                'Drop location must be valid text.',

            'drop_location.max' =>
                'Drop location may not exceed 255 characters.',

            /*
            |--------------------------------------------------------------------------
            | Travel Date & Time
            |--------------------------------------------------------------------------
            */

            'travel_date_time.required' =>
                'Travel date and time is required.',

            'travel_date_time.date' =>
                'Please enter a valid travel date and time.',

            /*
            |--------------------------------------------------------------------------
            | Passenger Count
            |--------------------------------------------------------------------------
            */

            'passenger_count.required' =>
                'Passenger count is required.',

            'passenger_count.integer' =>
                'Passenger count must be a valid number.',

            'passenger_count.min' =>
                'Passenger count must be at least 1.',

            'passenger_count.max' =>
                'Passenger count may not exceed 1000.',

            /*
            |--------------------------------------------------------------------------
            | Purpose
            |--------------------------------------------------------------------------
            */

            'purpose.string' =>
                'Purpose must be valid text.',

            'purpose.max' =>
                'Purpose may not exceed 500 characters.',

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Travel request status is required.',

            'status.in' =>
                'Selected travel request status is invalid.',

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks.string' =>
                'Remarks must be valid text.',

            'remarks.max' =>
                'Remarks may not exceed 2000 characters.',
        ];
    }
}