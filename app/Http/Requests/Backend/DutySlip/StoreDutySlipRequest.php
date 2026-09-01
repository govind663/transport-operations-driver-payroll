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
            | DUTY SLIP INFORMATION
            |--------------------------------------------------------------------------
            */

            'slip_no' => [
                'required',
                'string',
                'max:100',
                'unique:duty_slips,slip_no',
            ],

            'duty_assignment_id' => [
                'nullable',
                'integer',
                'exists:duty_assignments,id',
            ],

            'duty_date' => [
                'required',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | DRIVER
            |--------------------------------------------------------------------------
            */

            'driver_id' => [
                'required',
                'integer',
                'exists:drivers,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            'vehicle_id' => [
                'nullable',
                'integer',
                'exists:vehicles,id',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | TRIP INFORMATION
            |--------------------------------------------------------------------------
            */

            'start_date' => [
                'nullable',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'pickup_location' => [
                'nullable',
                'string',
                'max:500',
            ],

            'drop_location' => [
                'nullable',
                'string',
                'max:500',
            ],


            /*
            |--------------------------------------------------------------------------
            | KILOMETER INFORMATION
            |--------------------------------------------------------------------------
            */

            'opening_km' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'closing_km' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:opening_km',
            ],

            'total_km' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | PASSENGER INFORMATION
            |--------------------------------------------------------------------------
            */

            'passenger_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'passenger_mobile' => [
                'nullable',
                'digits:10',
            ],

            'number_of_passengers' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | DRIVER ALLOWANCES
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | driver_allowances[0][allowance_id]
            | driver_allowances[0][quantity]
            | driver_allowances[0][rate]
            | driver_allowances[0][amount]
            | driver_allowances[0][remarks]
            | driver_allowances[0][status]
            |
            */

            'driver_allowances' => [
                'nullable',
                'array',
            ],

            'driver_allowances.*.allowance_id' => [
                'required',
                'integer',
                'exists:allowances,id',
                'distinct',
            ],

            'driver_allowances.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'driver_allowances.*.rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'driver_allowances.*.amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'driver_allowances.*.remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'driver_allowances.*.status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'paid',
                    'cancelled',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | DRIVER EXPENSES
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | driver_expenses[0][expense_id]
            | driver_expenses[0][quantity]
            | driver_expenses[0][rate]
            | driver_expenses[0][amount]
            | driver_expenses[0][remarks]
            | driver_expenses[0][status]
            |
            */

            'driver_expenses' => [
                'nullable',
                'array',
            ],

            'driver_expenses.*.expense_id' => [
                'required',
                'integer',
                'exists:expenses,id',
                'distinct',
            ],

            'driver_expenses.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'driver_expenses.*.rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'driver_expenses.*.amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'driver_expenses.*.remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'driver_expenses.*.status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'paid',
                    'cancelled',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | FUEL INFORMATION
            |--------------------------------------------------------------------------
            |
            | These are vehicle/trip related fields.
            | They are NOT driver expense line items.
            |
            */

            'fuel_quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fuel_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP STATUS
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
            | REMARKS
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
            | DUTY SLIP
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
            | DUTY ASSIGNMENT
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
            | DRIVER
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
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            'vehicle_id.integer' =>
                'Invalid vehicle selected.',

            'vehicle_id.exists' =>
                'Selected vehicle does not exist.',


            /*
            |--------------------------------------------------------------------------
            | DUTY DATE
            |--------------------------------------------------------------------------
            */

            'duty_date.required' =>
                'Duty date is required.',

            'duty_date.date' =>
                'Please enter a valid duty date.',


            /*
            |--------------------------------------------------------------------------
            | START / END DATE
            |--------------------------------------------------------------------------
            */

            'start_date.date' =>
                'Please enter a valid start date.',

            'end_date.date' =>
                'Please enter a valid end date.',

            'end_date.after_or_equal' =>
                'End date cannot be before start date.',


            /*
            |--------------------------------------------------------------------------
            | START / END TIME
            |--------------------------------------------------------------------------
            */

            'start_time.date_format' =>
                'Start time must be in HH:MM format.',

            'end_time.date_format' =>
                'End time must be in HH:MM format.',


            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            'pickup_location.string' =>
                'Pickup location must be valid text.',

            'pickup_location.max' =>
                'Pickup location may not exceed 500 characters.',

            'drop_location.string' =>
                'Drop location must be valid text.',

            'drop_location.max' =>
                'Drop location may not exceed 500 characters.',


            /*
            |--------------------------------------------------------------------------
            | KM
            |--------------------------------------------------------------------------
            */

            'opening_km.numeric' =>
                'Opening KM must be a valid number.',

            'opening_km.min' =>
                'Opening KM cannot be negative.',

            'closing_km.numeric' =>
                'Closing KM must be a valid number.',

            'closing_km.min' =>
                'Closing KM cannot be negative.',

            'closing_km.gte' =>
                'Closing KM must be greater than or equal to opening KM.',

            'total_km.numeric' =>
                'Total KM must be a valid number.',

            'total_km.min' =>
                'Total KM cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | PASSENGER
            |--------------------------------------------------------------------------
            */

            'passenger_name.string' =>
                'Passenger name must be valid text.',

            'passenger_name.max' =>
                'Passenger name may not exceed 150 characters.',

            'passenger_mobile.digits' =>
                'Passenger mobile number must contain exactly 10 digits.',

            'number_of_passengers.integer' =>
                'Number of passengers must be a valid number.',

            'number_of_passengers.min' =>
                'At least one passenger is required.',


            /*
            |--------------------------------------------------------------------------
            | DRIVER ALLOWANCES
            |--------------------------------------------------------------------------
            */

            'driver_allowances.array' =>
                'Driver allowances must be provided in a valid format.',

            'driver_allowances.*.allowance_id.required' =>
                'Please select an allowance.',

            'driver_allowances.*.allowance_id.integer' =>
                'Invalid allowance selected.',

            'driver_allowances.*.allowance_id.exists' =>
                'Selected allowance does not exist.',

            'driver_allowances.*.allowance_id.distinct' =>
                'The same allowance cannot be added more than once.',

            'driver_allowances.*.quantity.required' =>
                'Allowance quantity is required.',

            'driver_allowances.*.quantity.numeric' =>
                'Allowance quantity must be a valid number.',

            'driver_allowances.*.quantity.min' =>
                'Allowance quantity must be greater than zero.',

            'driver_allowances.*.rate.required' =>
                'Allowance rate is required.',

            'driver_allowances.*.rate.numeric' =>
                'Allowance rate must be a valid number.',

            'driver_allowances.*.rate.min' =>
                'Allowance rate cannot be negative.',

            'driver_allowances.*.amount.required' =>
                'Allowance amount is required.',

            'driver_allowances.*.amount.numeric' =>
                'Allowance amount must be a valid number.',

            'driver_allowances.*.amount.min' =>
                'Allowance amount cannot be negative.',

            'driver_allowances.*.remarks.string' =>
                'Allowance remarks must be valid text.',

            'driver_allowances.*.remarks.max' =>
                'Allowance remarks may not exceed 1000 characters.',

            'driver_allowances.*.status.required' =>
                'Allowance status is required.',

            'driver_allowances.*.status.in' =>
                'Selected allowance status is invalid.',


            /*
            |--------------------------------------------------------------------------
            | DRIVER EXPENSES
            |--------------------------------------------------------------------------
            */

            'driver_expenses.array' =>
                'Driver expenses must be provided in a valid format.',

            'driver_expenses.*.expense_id.required' =>
                'Please select an expense.',

            'driver_expenses.*.expense_id.integer' =>
                'Invalid expense selected.',

            'driver_expenses.*.expense_id.exists' =>
                'Selected expense does not exist.',

            'driver_expenses.*.expense_id.distinct' =>
                'The same expense cannot be added more than once.',

            'driver_expenses.*.quantity.required' =>
                'Expense quantity is required.',

            'driver_expenses.*.quantity.numeric' =>
                'Expense quantity must be a valid number.',

            'driver_expenses.*.quantity.min' =>
                'Expense quantity must be greater than zero.',

            'driver_expenses.*.rate.required' =>
                'Expense rate is required.',

            'driver_expenses.*.rate.numeric' =>
                'Expense rate must be a valid number.',

            'driver_expenses.*.rate.min' =>
                'Expense rate cannot be negative.',

            'driver_expenses.*.amount.required' =>
                'Expense amount is required.',

            'driver_expenses.*.amount.numeric' =>
                'Expense amount must be a valid number.',

            'driver_expenses.*.amount.min' =>
                'Expense amount cannot be negative.',

            'driver_expenses.*.remarks.string' =>
                'Expense remarks must be valid text.',

            'driver_expenses.*.remarks.max' =>
                'Expense remarks may not exceed 1000 characters.',

            'driver_expenses.*.status.required' =>
                'Expense status is required.',

            'driver_expenses.*.status.in' =>
                'Selected expense status is invalid.',


            /*
            |--------------------------------------------------------------------------
            | FUEL
            |--------------------------------------------------------------------------
            */

            'fuel_quantity.numeric' =>
                'Fuel quantity must be a valid number.',

            'fuel_quantity.min' =>
                'Fuel quantity cannot be negative.',

            'fuel_amount.numeric' =>
                'Fuel amount must be a valid number.',

            'fuel_amount.min' =>
                'Fuel amount cannot be negative.',


            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP STATUS
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Duty slip status is required.',

            'status.in' =>
                'Selected duty slip status is invalid.',


            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            'remarks.string' =>
                'Remarks must be valid text.',

            'remarks.max' =>
                'Remarks may not exceed 2000 characters.',
        ];
    }
}