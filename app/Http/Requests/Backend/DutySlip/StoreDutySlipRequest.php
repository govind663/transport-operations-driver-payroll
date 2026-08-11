<?php

namespace App\Http\Requests\Backend\DutySlip;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDutySlipRequest extends FormRequest
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
            | Duty Slip Number
            |--------------------------------------------------------------------------
            */

            'slip_no' => [
                'required',
                'string',
                'max:100',
                'unique:duty_slips,slip_no',
            ],

            /*
            |--------------------------------------------------------------------------
            | Duty Assignment
            |--------------------------------------------------------------------------
            */

            'duty_assignment_id' => [
                'required',
                'integer',
                'exists:duty_assignments,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Duty Date
            |--------------------------------------------------------------------------
            */

            'duty_date' => [
                'required',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Start Time
            |--------------------------------------------------------------------------
            */

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | End Time
            |--------------------------------------------------------------------------
            */

            'end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | Opening Meter
            |--------------------------------------------------------------------------
            */

            'opening_meter' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Closing Meter
            |--------------------------------------------------------------------------
            */

            'closing_meter' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:opening_meter',
            ],

            /*
            |--------------------------------------------------------------------------
            | Total KM
            |--------------------------------------------------------------------------
            */

            'total_km' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Fuel Quantity
            |--------------------------------------------------------------------------
            */

            'fuel_quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Fuel Amount
            |--------------------------------------------------------------------------
            */

            'fuel_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                Rule::in([
                    'open',
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
            | Slip Number
            |--------------------------------------------------------------------------
            */

            'slip_no.required' =>
                'Duty slip number is required.',

            'slip_no.string' =>
                'Duty slip number must be valid text.',

            'slip_no.max' =>
                'Duty slip number may not exceed 100 characters.',

            'slip_no.unique' =>
                'This duty slip number already exists.',


            /*
            |--------------------------------------------------------------------------
            | Duty Assignment
            |--------------------------------------------------------------------------
            */

            'duty_assignment_id.required' =>
                'Please select a duty assignment.',

            'duty_assignment_id.integer' =>
                'Invalid duty assignment selected.',

            'duty_assignment_id.exists' =>
                'Selected duty assignment does not exist.',


            /*
            |--------------------------------------------------------------------------
            | Duty Date
            |--------------------------------------------------------------------------
            */

            'duty_date.required' =>
                'Duty date is required.',

            'duty_date.date' =>
                'Please enter a valid duty date.',


            /*
            |--------------------------------------------------------------------------
            | Start Time
            |--------------------------------------------------------------------------
            */

            'start_time.date_format' =>
                'Start time must be in HH:MM format.',


            /*
            |--------------------------------------------------------------------------
            | End Time
            |--------------------------------------------------------------------------
            */

            'end_time.date_format' =>
                'End time must be in HH:MM format.',


            /*
            |--------------------------------------------------------------------------
            | Opening Meter
            |--------------------------------------------------------------------------
            */

            'opening_meter.numeric' =>
                'Opening meter must be a valid number.',

            'opening_meter.min' =>
                'Opening meter cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Closing Meter
            |--------------------------------------------------------------------------
            */

            'closing_meter.numeric' =>
                'Closing meter must be a valid number.',

            'closing_meter.min' =>
                'Closing meter cannot be negative.',

            'closing_meter.gte' =>
                'Closing meter must be greater than or equal to opening meter.',


            /*
            |--------------------------------------------------------------------------
            | Total KM
            |--------------------------------------------------------------------------
            */

            'total_km.numeric' =>
                'Total KM must be a valid number.',

            'total_km.min' =>
                'Total KM cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Fuel Quantity
            |--------------------------------------------------------------------------
            */

            'fuel_quantity.numeric' =>
                'Fuel quantity must be a valid number.',

            'fuel_quantity.min' =>
                'Fuel quantity cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Fuel Amount
            |--------------------------------------------------------------------------
            */

            'fuel_amount.numeric' =>
                'Fuel amount must be a valid number.',

            'fuel_amount.min' =>
                'Fuel amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Duty slip status is required.',

            'status.in' =>
                'Selected duty slip status is invalid.',


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