<?php

namespace App\Http\Requests\Backend\DutySlip;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateDutySlipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE FOR VALIDATION
    |--------------------------------------------------------------------------
    |
    | Legacy aliases:
    | allowances[] -> driver_allowances[]
    | expenses[]   -> driver_expenses[]
    |
    | Duty Slip Number is normalized before unique validation.
    | Location values are trimmed before validation.
    |
    */

    protected function prepareForValidation(): void
    {
        $data = [];

        /*
        |--------------------------------------------------------------------------
        | LEGACY ALLOWANCES
        |--------------------------------------------------------------------------
        */

        if (
            $this->has('allowances') &&
            !$this->has('driver_allowances')
        ) {
            $data['driver_allowances'] = $this->input('allowances');
        }

        /*
        |--------------------------------------------------------------------------
        | LEGACY EXPENSES
        |--------------------------------------------------------------------------
        */

        if (
            $this->has('expenses') &&
            !$this->has('driver_expenses')
        ) {
            $data['driver_expenses'] = $this->input('expenses');
        }

        /*
        |--------------------------------------------------------------------------
        | DUTY SLIP NUMBER
        |--------------------------------------------------------------------------
        |
        | Example:
        | " ds 000125 " -> "DS000125"
        |
        */

        if ($this->has('slip_no')) {
            $slipNo = trim(
                (string) $this->input('slip_no')
            );

            $slipNo = preg_replace('/\s+/', '', $slipNo);

            $data['slip_no'] = (
                $slipNo !== null && $slipNo !== ''
            )
                ? strtoupper($slipNo)
                : null;
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE EMPTY OPTIONAL IDs
        |--------------------------------------------------------------------------
        */

        foreach ([
            'driver_id',
            'vehicle_id',
            'vehicle_type_id',
        ] as $field) {
            if ($this->input($field) === '') {
                $data[$field] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PICKUP AND DROP LOCATION
        |--------------------------------------------------------------------------
        |
        | Trim surrounding whitespace.
        | Convert an empty string to null.
        |
        */

        foreach ([
            'pickup_location',
            'drop_location',
        ] as $field) {
            if (is_string($this->input($field))) {
                $value = trim($this->input($field));

                $data[$field] = $value !== ''
                    ? $value
                    : null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MERGE NORMALIZED DATA
        |--------------------------------------------------------------------------
        */

        if (!empty($data)) {
            $this->merge($data);
        }
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
        | CURRENT DUTY SLIP
        |--------------------------------------------------------------------------
        |
        | Supports either {duty_slip} or {dutySlip} route parameters.
        |
        */

        $dutySlip = $this->route('duty_slip')
            ?? $this->route('dutySlip');

        $dutySlipId = is_object($dutySlip)
            ? $dutySlip->getKey()
            : $dutySlip;

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
                Rule::unique('duty_slips', 'slip_no')
                    ->ignore($dutySlipId),
            ],

            'duty_assignment_id' => [
                'required',
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
                'required',
                'integer',
                'exists:vehicle_management,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | VEHICLE TYPE
            |--------------------------------------------------------------------------
            */

            'vehicle_type_id' => [
                'required',
                'integer',
                'exists:vehicle_types,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | LEGACY VEHICLE TYPE
            |--------------------------------------------------------------------------
            */

            'vehicle_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | TRIP DATE / TIME
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

            /*
            |--------------------------------------------------------------------------
            | PICKUP LOCATION
            |--------------------------------------------------------------------------
            |
            | Stored in duty_slips.pickup_location.
            |
            */

            'pickup_location' => [
                'nullable',
                'string',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------------------
            | DROP LOCATION
            |--------------------------------------------------------------------------
            |
            | Stored in duty_slips.drop_location.
            |
            */

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

            /*
             * Final total is recalculated by DutySlipService.
             */

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
            | An empty dynamic row is allowed.
            | Selecting an allowance makes quantity and status required.
            |
            */

            'driver_allowances' => [
                'nullable',
                'array',
                'max:50',
            ],

            /*
            | Existing child record must belong to this Duty Slip.
            */

            'driver_allowances.*.id' => [
                'nullable',
                'integer',
                Rule::exists('driver_allowances', 'id')
                    ->where('duty_slip_id', $dutySlipId),
            ],

            'driver_allowances.*.allowance_id' => [
                'nullable',
                'integer',
                'exists:allowances,id',
                'distinct',
            ],

            'driver_allowances.*.quantity' => [
                'required_with:driver_allowances.*.allowance_id',
                'numeric',
                'min:0.01',
            ],

            /*
             * Allowance rates continue to come from Allowance Master.
             */

            'driver_allowances.*.rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'driver_allowances.*.amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'driver_allowances.*.remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'driver_allowances.*.status' => [
                'required_with:driver_allowances.*.allowance_id',
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
            | Expense Master provides the Expense Type/Name.
            | Actual Rate is entered for this particular transaction.
            |
            */

            'driver_expenses' => [
                'nullable',
                'array',
                'max:50',
            ],

            /*
            | Existing expense record must belong to this Duty Slip.
            */

            'driver_expenses.*.id' => [
                'nullable',
                'integer',
                Rule::exists('driver_expenses', 'id')
                    ->where('duty_slip_id', $dutySlipId),
            ],

            'driver_expenses.*.expense_id' => [
                'nullable',
                'integer',
                'exists:expenses,id',
                'distinct',
            ],

            /*
            |--------------------------------------------------------------------------
            | EXPENSE QUANTITY
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.quantity' => [
                'required_with:driver_expenses.*.expense_id',
                'numeric',
                'min:0.01',
            ],

            /*
            |--------------------------------------------------------------------------
            | EXPENSE RATE
            |--------------------------------------------------------------------------
            |
            | Actual rate must be entered manually.
            | No rate is taken from the Expense Master.
            |
            */

            'driver_expenses.*.rate' => [
                'required_with:driver_expenses.*.expense_id',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | EXPENSE AMOUNT
            |--------------------------------------------------------------------------
            |
            | Service recalculates amount as Quantity x Rate.
            |
            */

            'driver_expenses.*.amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | EXPENSE DOCUMENT
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.document_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'driver_expenses.*.remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'driver_expenses.*.status' => [
                'required_with:driver_expenses.*.expense_id',
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
            | DUTY SLIP FRONT FILE
            |--------------------------------------------------------------------------
            */

            'duty_slip_front_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP BACK FILE
            |--------------------------------------------------------------------------
            */

            'duty_slip_back_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
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

    /*
    |--------------------------------------------------------------------------
    | ADDITIONAL BUSINESS VALIDATION
    |--------------------------------------------------------------------------
    */

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(function (Validator $validator): void {

            /*
            |--------------------------------------------------------------------------
            | START / END DATETIME VALIDATION
            |--------------------------------------------------------------------------
            */

            $startDate = $this->input('start_date')
                ?: $this->input('duty_date');

            $endDate = $this->input('end_date')
                ?: $this->input('duty_date');

            $startTime = $this->input('start_time');
            $endTime = $this->input('end_time');

            if (
                !empty($startDate) &&
                !empty($endDate) &&
                !empty($startTime) &&
                !empty($endTime)
            ) {
                try {
                    $startDateTime = \Carbon\Carbon::createFromFormat(
                        'Y-m-d H:i',
                        "{$startDate} {$startTime}"
                    );

                    $endDateTime = \Carbon\Carbon::createFromFormat(
                        'Y-m-d H:i',
                        "{$endDate} {$endTime}"
                    );

                    if (
                        $endDateTime->lessThan($startDateTime)
                    ) {
                        $validator->errors()->add(
                            'end_time',
                            'End date and time cannot be before start date and time.'
                        );
                    }
                } catch (\Throwable $exception) {
                    // Standard date/time rules handle malformed input.
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION MESSAGES
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP NUMBER
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
            | DUTY DATE
            |--------------------------------------------------------------------------
            */

            'duty_date.required' =>
                'Duty date is required.',

            'duty_date.date' =>
                'Please enter a valid duty date.',

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

            'vehicle_id.required' =>
                'Please select a vehicle.',

            'vehicle_id.integer' =>
                'Invalid vehicle selected.',

            'vehicle_id.exists' =>
                'Selected vehicle does not exist.',

            /*
            |--------------------------------------------------------------------------
            | VEHICLE TYPE
            |--------------------------------------------------------------------------
            */

            'vehicle_type_id.required' =>
                'Please select a vehicle type.',

            'vehicle_type_id.integer' =>
                'Invalid vehicle type selected.',

            'vehicle_type_id.exists' =>
                'Selected vehicle type does not exist.',

            /*
            |--------------------------------------------------------------------------
            | LEGACY VEHICLE TYPE
            |--------------------------------------------------------------------------
            */

            'vehicle_type.string' =>
                'Vehicle type must be valid text.',

            'vehicle_type.max' =>
                'Vehicle type may not exceed 100 characters.',

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
            | PICKUP LOCATION
            |--------------------------------------------------------------------------
            */

            'pickup_location.string' =>
                'Pickup location must be valid text.',

            'pickup_location.max' =>
                'Pickup location may not exceed 500 characters.',

            /*
            |--------------------------------------------------------------------------
            | DROP LOCATION
            |--------------------------------------------------------------------------
            */

            'drop_location.string' =>
                'Drop location must be valid text.',

            'drop_location.max' =>
                'Drop location may not exceed 500 characters.',

            /*
            |--------------------------------------------------------------------------
            | KILOMETER INFORMATION
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
            | PASSENGER INFORMATION
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

            'driver_allowances.max' =>
                'Too many driver allowance rows were submitted.',

            'driver_allowances.*.id.integer' =>
                'Invalid driver allowance record.',

            'driver_allowances.*.id.exists' =>
                'Selected allowance record does not belong to this duty slip.',

            'driver_allowances.*.allowance_id.required' =>
                'Please select an allowance.',

            'driver_allowances.*.allowance_id.integer' =>
                'Invalid allowance selected.',

            'driver_allowances.*.allowance_id.exists' =>
                'Selected allowance does not exist.',

            'driver_allowances.*.allowance_id.distinct' =>
                'The same allowance cannot be added more than once.',

            'driver_allowances.*.quantity.required_with' =>
                'Allowance quantity is required when an allowance is selected.',

            'driver_allowances.*.quantity.numeric' =>
                'Allowance quantity must be a valid number.',

            'driver_allowances.*.quantity.min' =>
                'Allowance quantity must be greater than zero.',

            'driver_allowances.*.rate.numeric' =>
                'Allowance rate must be a valid number.',

            'driver_allowances.*.rate.min' =>
                'Allowance rate cannot be negative.',

            'driver_allowances.*.amount.numeric' =>
                'Allowance amount must be a valid number.',

            'driver_allowances.*.amount.min' =>
                'Allowance amount cannot be negative.',

            'driver_allowances.*.remarks.string' =>
                'Allowance remarks must be valid text.',

            'driver_allowances.*.remarks.max' =>
                'Allowance remarks may not exceed 1000 characters.',

            'driver_allowances.*.status.required_with' =>
                'Allowance status is required when an allowance is selected.',

            'driver_allowances.*.status.in' =>
                'Selected allowance status is invalid.',

            /*
            |--------------------------------------------------------------------------
            | DRIVER EXPENSES
            |--------------------------------------------------------------------------
            */

            'driver_expenses.array' =>
                'Driver expenses must be provided in a valid format.',

            'driver_expenses.max' =>
                'Too many driver expense rows were submitted.',

            'driver_expenses.*.id.integer' =>
                'Invalid driver expense record.',

            'driver_expenses.*.id.exists' =>
                'Selected expense record does not belong to this duty slip.',

            'driver_expenses.*.expense_id.required' =>
                'Please select an expense.',

            'driver_expenses.*.expense_id.integer' =>
                'Invalid expense selected.',

            'driver_expenses.*.expense_id.exists' =>
                'Selected expense does not exist.',

            'driver_expenses.*.expense_id.distinct' =>
                'The same expense cannot be added more than once.',

            'driver_expenses.*.quantity.required_with' =>
                'Expense quantity is required when an expense is selected.',

            'driver_expenses.*.quantity.numeric' =>
                'Expense quantity must be a valid number.',

            'driver_expenses.*.quantity.min' =>
                'Expense quantity must be greater than zero.',

            'driver_expenses.*.rate.required_with' =>
                'Expense rate is required when an expense is selected.',

            'driver_expenses.*.rate.numeric' =>
                'Expense rate must be a valid number.',

            'driver_expenses.*.rate.min' =>
                'Expense rate cannot be negative.',

            'driver_expenses.*.amount.numeric' =>
                'Expense amount must be a valid number.',

            'driver_expenses.*.amount.min' =>
                'Expense amount cannot be negative.',

            /*
            |--------------------------------------------------------------------------
            | EXPENSE DOCUMENT
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.document_file.file' =>
                'Expense document must be a valid file.',

            'driver_expenses.*.document_file.mimes' =>
                'Expense document must be a PDF, JPG, JPEG, or PNG file.',

            'driver_expenses.*.document_file.max' =>
                'Expense document may not exceed 5 MB.',

            /*
            |--------------------------------------------------------------------------
            | EXPENSE REMARKS
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.remarks.string' =>
                'Expense remarks must be valid text.',

            'driver_expenses.*.remarks.max' =>
                'Expense remarks may not exceed 1000 characters.',

            /*
            |--------------------------------------------------------------------------
            | EXPENSE STATUS
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.status.required_with' =>
                'Expense status is required when an expense is selected.',

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
            | DUTY SLIP FRONT FILE
            |--------------------------------------------------------------------------
            */

            'duty_slip_front_file.file' =>
                'Please upload a valid duty slip front file.',

            'duty_slip_front_file.mimes' =>
                'Duty slip front file must be a PDF, JPG, JPEG, or PNG file.',

            'duty_slip_front_file.max' =>
                'Duty slip front file may not exceed 5 MB.',

            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP BACK FILE
            |--------------------------------------------------------------------------
            */

            'duty_slip_back_file.file' =>
                'Please upload a valid duty slip back file.',

            'duty_slip_back_file.mimes' =>
                'Duty slip back file must be a PDF, JPG, JPEG, or PNG file.',

            'duty_slip_back_file.max' =>
                'Duty slip back file may not exceed 5 MB.',

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