<?php

namespace App\Http\Requests\Backend\DutyAssignment;

use App\Models\DutyAssignment;
use App\Models\VehicleManagement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDutyAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            */

            'assigned_at' => [
                'nullable',
                'date_format:Y-m-d',
            ],


            /*
            |--------------------------------------------------------------------------
            | Reporting Time
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