<?php

namespace App\Http\Requests\Backend\WorkingSheet;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkingSheetRequest extends FormRequest
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
        | Current Working Sheet
        |--------------------------------------------------------------------------
        */

        $workingSheet = $this->route('working_sheet');


        /*
        |--------------------------------------------------------------------------
        | Working Sheet ID
        |--------------------------------------------------------------------------
        */

        $workingSheetId = is_object($workingSheet)
            ? $workingSheet->id
            : $workingSheet;


        return [

            /*
            |--------------------------------------------------------------------------
            | Working Sheet Number
            |--------------------------------------------------------------------------
            */

            'sheet_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('working_sheets', 'sheet_no')
                    ->ignore($workingSheetId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id' => [
                'required',
                'integer',
                'exists:drivers,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Duty Slip
            |--------------------------------------------------------------------------
            */

            'duty_slip_id' => [
                'required',
                'integer',
                'exists:duty_slips,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Work Date
            |--------------------------------------------------------------------------
            */

            'work_date' => [
                'required',
                'date',
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
            | Total Hours
            |--------------------------------------------------------------------------
            */

            'total_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Overtime Hours
            |--------------------------------------------------------------------------
            */

            'overtime_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Base Amount
            |--------------------------------------------------------------------------
            */

            'base_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Extra KM Amount
            |--------------------------------------------------------------------------
            */

            'extra_km_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Overtime Amount
            |--------------------------------------------------------------------------
            */

            'overtime_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Other Amount
            |--------------------------------------------------------------------------
            */

            'other_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Total Amount
            |--------------------------------------------------------------------------
            */

            'total_amount' => [
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
                    'draft',
                    'submitted',
                    'approved',
                    'rejected',
                    'completed',
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
            | Sheet Number
            |--------------------------------------------------------------------------
            */

            'sheet_no.required' =>
                'Working sheet number is required.',

            'sheet_no.string' =>
                'Working sheet number must be a valid text.',

            'sheet_no.max' =>
                'Working sheet number may not exceed 100 characters.',

            'sheet_no.unique' =>
                'This working sheet number already exists.',


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id.required' =>
                'Please select a driver.',

            'driver_id.integer' =>
                'Invalid driver selected.',

            'driver_id.exists' =>
                'Selected driver does not exist.',


            /*
            |--------------------------------------------------------------------------
            | Duty Slip
            |--------------------------------------------------------------------------
            */

            'duty_slip_id.required' =>
                'Please select a duty slip.',

            'duty_slip_id.integer' =>
                'Invalid duty slip selected.',

            'duty_slip_id.exists' =>
                'Selected duty slip does not exist.',


            /*
            |--------------------------------------------------------------------------
            | Work Date
            |--------------------------------------------------------------------------
            */

            'work_date.required' =>
                'Work date is required.',

            'work_date.date' =>
                'Please enter a valid work date.',


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
            | Total Hours
            |--------------------------------------------------------------------------
            */

            'total_hours.numeric' =>
                'Total hours must be a valid number.',

            'total_hours.min' =>
                'Total hours cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Overtime Hours
            |--------------------------------------------------------------------------
            */

            'overtime_hours.numeric' =>
                'Overtime hours must be a valid number.',

            'overtime_hours.min' =>
                'Overtime hours cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Base Amount
            |--------------------------------------------------------------------------
            */

            'base_amount.numeric' =>
                'Base amount must be a valid number.',

            'base_amount.min' =>
                'Base amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Extra KM Amount
            |--------------------------------------------------------------------------
            */

            'extra_km_amount.numeric' =>
                'Extra KM amount must be a valid number.',

            'extra_km_amount.min' =>
                'Extra KM amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Overtime Amount
            |--------------------------------------------------------------------------
            */

            'overtime_amount.numeric' =>
                'Overtime amount must be a valid number.',

            'overtime_amount.min' =>
                'Overtime amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Other Amount
            |--------------------------------------------------------------------------
            */

            'other_amount.numeric' =>
                'Other amount must be a valid number.',

            'other_amount.min' =>
                'Other amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Total Amount
            |--------------------------------------------------------------------------
            */

            'total_amount.numeric' =>
                'Total amount must be a valid number.',

            'total_amount.min' =>
                'Total amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Working sheet status is required.',

            'status.in' =>
                'Selected working sheet status is invalid.',


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