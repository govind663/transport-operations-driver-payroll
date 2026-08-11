<?php

namespace App\Http\Requests\Backend\DutyAssignment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDutyAssignmentRequest extends FormRequest
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
        | Current Duty Assignment
        |--------------------------------------------------------------------------
        */

        $dutyAssignment = $this->route('duty_assignment');

        $dutyAssignmentId = is_object($dutyAssignment)
            ? $dutyAssignment->id
            : $dutyAssignment;

        return [

            /*
            |--------------------------------------------------------------------------
            | Assignment Number
            |--------------------------------------------------------------------------
            */

            'assignment_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique(
                    'duty_assignments',
                    'assignment_no'
                )->ignore($dutyAssignmentId),
            ],

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
                'exists:vehicle_managements,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Assigned At
            |--------------------------------------------------------------------------
            */

            'assigned_at' => [
                'nullable',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reporting Time
            |--------------------------------------------------------------------------
            */

            'reporting_time' => [
                'nullable',
                'date',
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
                    'pending',
                    'assigned',
                    'accepted',
                    'rejected',
                    'started',
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
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Assignment Number
            |--------------------------------------------------------------------------
            */

            'assignment_no.required' =>
                'Assignment number is required.',

            'assignment_no.string' =>
                'Assignment number must be valid text.',

            'assignment_no.max' =>
                'Assignment number may not exceed 100 characters.',

            'assignment_no.unique' =>
                'This assignment number already exists.',

            /*
            |--------------------------------------------------------------------------
            | Travel Request
            |--------------------------------------------------------------------------
            */

            'travel_request_id.required' =>
                'Please select a travel request.',

            'travel_request_id.integer' =>
                'Travel request must be valid.',

            'travel_request_id.exists' =>
                'Selected travel request does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id.integer' =>
                'Driver must be valid.',

            'driver_id.exists' =>
                'Selected driver does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Vehicle
            |--------------------------------------------------------------------------
            */

            'vehicle_id.integer' =>
                'Vehicle must be valid.',

            'vehicle_id.exists' =>
                'Selected vehicle does not exist.',

            /*
            |--------------------------------------------------------------------------
            | Assigned At
            |--------------------------------------------------------------------------
            */

            'assigned_at.date' =>
                'Please enter a valid assignment date and time.',

            /*
            |--------------------------------------------------------------------------
            | Reporting Time
            |--------------------------------------------------------------------------
            */

            'reporting_time.date' =>
                'Please enter a valid reporting date and time.',

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