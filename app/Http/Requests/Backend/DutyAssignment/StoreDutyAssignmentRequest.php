<?php

namespace App\Http\Requests\Backend\DutyAssignment;

use App\Models\DutyAssignment;
use App\Models\VehicleManagement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDutyAssignmentRequest extends FormRequest
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
        return [

            /*
            |--------------------------------------------------------------------------
            | Travel Request
            |--------------------------------------------------------------------------
            */

            'travel_request_id' => [
                'required',
                'integer',
                'exists:travel_requests,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id' => [
                'nullable',
                'integer',
                'exists:drivers,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Vehicle
            |--------------------------------------------------------------------------
            */

            'vehicle_id' => [
                'nullable',
                'integer',
                Rule::exists(
                    (new VehicleManagement)->getTable(),
                    'id'
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Assigned At
            |--------------------------------------------------------------------------
            | Blade: <input type="date">
            | Expected: YYYY-MM-DD
            |--------------------------------------------------------------------------
            */

            'assigned_at' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reporting Time
            |--------------------------------------------------------------------------
            | Blade: <input type="time">
            | Expected: HH:MM
            |--------------------------------------------------------------------------
            */

            'reporting_time' => [
                'nullable',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reporting Location
            |--------------------------------------------------------------------------
            */

            'reporting_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                Rule::in([
                    DutyAssignment::STATUS_PENDING,
                    DutyAssignment::STATUS_ASSIGNED,
                    DutyAssignment::STATUS_ACCEPTED,
                    DutyAssignment::STATUS_REJECTED,
                    DutyAssignment::STATUS_STARTED,
                    DutyAssignment::STATUS_COMPLETED,
                    DutyAssignment::STATUS_CANCELLED,
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
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Travel Request
            |--------------------------------------------------------------------------
            */

            'travel_request_id.required' =>
                'Please select a travel request.',

            'travel_request_id.integer' =>
                'Selected travel request is invalid.',

            'travel_request_id.exists' =>
                'Selected travel request does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id.integer' =>
                'Selected driver is invalid.',

            'driver_id.exists' =>
                'Selected driver does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Vehicle
            |--------------------------------------------------------------------------
            */

            'vehicle_id.integer' =>
                'Selected vehicle is invalid.',

            'vehicle_id.exists' =>
                'Selected vehicle does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Assigned At
            |--------------------------------------------------------------------------
            */

            'assigned_at.date_format' =>
                'Please enter a valid assignment date.',

            /*
            |--------------------------------------------------------------------------
            | Reporting Time
            |--------------------------------------------------------------------------
            */

            'reporting_time.date_format' =>
                'Please enter a valid reporting time.',

            /*
            |--------------------------------------------------------------------------
            | Reporting Location
            |--------------------------------------------------------------------------
            */

            'reporting_location.string' =>
                'Reporting location must be valid text.',

            'reporting_location.max' =>
                'Reporting location may not exceed 255 characters.',

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Assignment status is required.',

            'status.in' =>
                'Selected assignment status is invalid.',

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