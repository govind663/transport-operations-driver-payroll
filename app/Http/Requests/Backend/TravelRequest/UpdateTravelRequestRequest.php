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
            | Request Information
            |--------------------------------------------------------------------------
            */

            'request_no' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(
                    'travel_requests',
                    'request_no'
                )->ignore($travelRequestId),
            ],

            'client_id' => [
                'required',
                'integer',
                'exists:clients,id',
            ],

            'requested_by' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Employee / Travel Information
            |--------------------------------------------------------------------------
            */

            'employee_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'travel_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'trip_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Vendor / Vehicle
            |--------------------------------------------------------------------------
            */

            'vendor_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Travel Date / Time
            |--------------------------------------------------------------------------
            */

            'travel_from_date' => [
                'nullable',
                'date',
            ],

            'travel_to_date' => [
                'nullable',
                'date',
                'after_or_equal:travel_from_date',
            ],

            'pickup_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'release_time' => [
                'nullable',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'from_city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_location' => [
                'required',
                'string',
                'max:255',
            ],

            'drop_location' => [
                'required',
                'string',
                'max:255',
            ],

            'release_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'reporting_address' => [
                'nullable',
                'string',
            ],

            'release_address' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Passenger Information
            |--------------------------------------------------------------------------
            */

            'passenger_name' => [
                'required',
                'string',
                'max:255',
            ],

            'passenger_phone' => [
                'nullable',
                'string',
                'max:255',
            ],

            'traveler_mobile' => [
                'nullable',
                'string',
                'max:20',
            ],

            'passenger_count' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Employee Details
            |--------------------------------------------------------------------------
            */

            'employee_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'cost_center' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Car Hire / Usage
            |--------------------------------------------------------------------------
            */

            'car_hire_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'for_use' => [
                'nullable',
                'string',
                'max:100',
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

            /*
            |--------------------------------------------------------------------------
            | Existing Travel Date Time
            |--------------------------------------------------------------------------
            */

            'travel_date_time' => [
                'nullable',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Purpose / Instructions
            |--------------------------------------------------------------------------
            */

            'purpose' => [
                'nullable',
                'string',
            ],

            'specific_instruction' => [
                'nullable',
                'string',
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
            | Request Information
            |--------------------------------------------------------------------------
            */

            'request_no' => $this->filled('request_no')
                ? strtoupper(trim($this->request_no))
                : null,

            /*
            |--------------------------------------------------------------------------
            | Employee / Travel Information
            |--------------------------------------------------------------------------
            */

            'employee_email' => $this->filled('employee_email')
                ? strtolower(trim($this->employee_email))
                : null,

            'travel_id' => $this->filled('travel_id')
                ? trim($this->travel_id)
                : null,

            'trip_id' => $this->filled('trip_id')
                ? trim($this->trip_id)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Vendor / Vehicle
            |--------------------------------------------------------------------------
            */

            'vendor_name' => $this->filled('vendor_name')
                ? $this->cleanText($this->vendor_name)
                : null,

            'vehicle_type' => $this->filled('vehicle_type')
                ? $this->cleanText($this->vehicle_type)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Travel Information
            |--------------------------------------------------------------------------
            */

            'travel_from_date' => $this->filled('travel_from_date')
                ? trim($this->travel_from_date)
                : null,

            'travel_to_date' => $this->filled('travel_to_date')
                ? trim($this->travel_to_date)
                : null,

            'pickup_time' => $this->filled('pickup_time')
                ? trim($this->pickup_time)
                : null,

            'release_time' => $this->filled('release_time')
                ? trim($this->release_time)
                : null,

            'from_city' => $this->filled('from_city')
                ? $this->cleanText($this->from_city)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Locations
            |--------------------------------------------------------------------------
            */

            'pickup_location' => $this->filled('pickup_location')
                ? $this->cleanText($this->pickup_location)
                : null,

            'drop_location' => $this->filled('drop_location')
                ? $this->cleanText($this->drop_location)
                : null,

            'release_location' => $this->filled('release_location')
                ? $this->cleanText($this->release_location)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Addresses
            |--------------------------------------------------------------------------
            */

            'reporting_address' => $this->filled('reporting_address')
                ? trim($this->reporting_address)
                : null,

            'release_address' => $this->filled('release_address')
                ? trim($this->release_address)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Passenger
            |--------------------------------------------------------------------------
            */

            'passenger_name' => $this->filled('passenger_name')
                ? $this->cleanText($this->passenger_name)
                : null,

            'passenger_phone' => $this->filled('passenger_phone')
                ? $this->cleanPhone($this->passenger_phone)
                : null,

            'traveler_mobile' => $this->filled('traveler_mobile')
                ? $this->cleanPhone($this->traveler_mobile)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            'employee_id' => $this->filled('employee_id')
                ? trim($this->employee_id)
                : null,

            'cost_center' => $this->filled('cost_center')
                ? trim($this->cost_center)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Car Hire / Usage
            |--------------------------------------------------------------------------
            */

            'car_hire_type' => $this->filled('car_hire_type')
                ? trim($this->car_hire_type)
                : null,

            'for_use' => $this->filled('for_use')
                ? trim($this->for_use)
                : null,

            /*
            |--------------------------------------------------------------------------
            | GST
            |--------------------------------------------------------------------------
            */

            'gst_number' => $this->filled('gst_number')
                ? strtoupper(trim($this->gst_number))
                : null,

            /*
            |--------------------------------------------------------------------------
            | Purpose / Instructions
            |--------------------------------------------------------------------------
            */

            'purpose' => $this->filled('purpose')
                ? trim($this->purpose)
                : null,

            'specific_instruction' => $this->filled('specific_instruction')
                ? trim($this->specific_instruction)
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
     * Clean normal text fields.
     */
    private function cleanText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return preg_replace('/\s+/', ' ', trim($value));
    }

    /**
     * Clean phone number.
     */
    private function cleanPhone(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return preg_replace('/[^0-9+]/', '', trim($value));
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'request_no.string' =>
                'Request number must be valid text.',

            'request_no.max' =>
                'Request number may not exceed 255 characters.',

            'request_no.unique' =>
                'This travel request number already exists.',

            'client_id.required' =>
                'Please select a client.',

            'client_id.integer' =>
                'Selected client is invalid.',

            'client_id.exists' =>
                'Selected client does not exist.',

            'requested_by.string' =>
                'Requested by must be valid text.',

            'requested_by.max' =>
                'Requested by may not exceed 255 characters.',

            'employee_email.email' =>
                'Please enter a valid employee email address.',

            'employee_email.max' =>
                'Employee email may not exceed 255 characters.',

            'travel_id.max' =>
                'Travel ID may not exceed 100 characters.',

            'trip_id.max' =>
                'Trip ID may not exceed 100 characters.',

            'vendor_name.max' =>
                'Vendor name may not exceed 255 characters.',

            'vehicle_type.max' =>
                'Vehicle type may not exceed 100 characters.',

            'travel_from_date.date' =>
                'Please enter a valid travel from date.',

            'travel_to_date.date' =>
                'Please enter a valid travel to date.',

            'travel_to_date.after_or_equal' =>
                'Travel to date must be equal to or after the travel from date.',

            'pickup_time.date_format' =>
                'Pickup time must be in HH:MM format.',

            'release_time.date_format' =>
                'Release time must be in HH:MM format.',

            'from_city.max' =>
                'From city may not exceed 255 characters.',

            'passenger_name.required' =>
                'Passenger name is required.',

            'passenger_name.max' =>
                'Passenger name may not exceed 255 characters.',

            'passenger_phone.max' =>
                'Passenger phone may not exceed 255 characters.',

            'traveler_mobile.max' =>
                'Traveler mobile may not exceed 20 characters.',

            'employee_id.max' =>
                'Employee ID may not exceed 100 characters.',

            'cost_center.max' =>
                'Cost center may not exceed 100 characters.',

            'car_hire_type.max' =>
                'Car hire type may not exceed 50 characters.',

            'for_use.max' =>
                'For use may not exceed 100 characters.',

            'gst_number.max' =>
                'GST number may not exceed 20 characters.',

            'pickup_location.required' =>
                'Pickup location is required.',

            'pickup_location.max' =>
                'Pickup location may not exceed 255 characters.',

            'drop_location.required' =>
                'Drop location is required.',

            'drop_location.max' =>
                'Drop location may not exceed 255 characters.',

            'release_location.max' =>
                'Release location may not exceed 255 characters.',

            'passenger_count.required' =>
                'Passenger count is required.',

            'passenger_count.integer' =>
                'Passenger count must be a valid number.',

            'passenger_count.min' =>
                'Passenger count must be at least 1.',

            'passenger_count.max' =>
                'Passenger count may not exceed 1000.',

            'travel_date_time.date' =>
                'Please enter a valid travel date and time.',

            'status.required' =>
                'Travel request status is required.',

            'status.in' =>
                'Selected travel request status is invalid.',
        ];
    }
}