<?php

namespace App\Http\Requests\Backend\DutySlip;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreDutySlipRequest extends FormRequest
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
    | Backward compatibility:
    |
    | allowances[]       -> driver_allowances[]
    | expenses[]         -> driver_expenses[]
    |
    | Vehicle Type:
    |
    | vehicle_type_id is the preferred field.
    |
    | If an older Blade still sends vehicle_type, it is retained so the
    | service can resolve it for backward compatibility.
    |
    */

    protected function prepareForValidation(): void
    {
        $data = [];


        /*
        |--------------------------------------------------------------------------
        | ALLOWANCES
        |--------------------------------------------------------------------------
        */

        if (
            $this->has('allowances') &&
            !$this->has('driver_allowances')
        ) {
            $data['driver_allowances'] =
                $this->input('allowances');
        }


        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        if (
            $this->has('expenses') &&
            !$this->has('driver_expenses')
        ) {
            $data['driver_expenses'] =
                $this->input('expenses');
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE EMPTY VALUES
        |--------------------------------------------------------------------------
        */

        if (
            $this->input('slip_no') === ''
        ) {
            $data['slip_no'] = null;
        }


        if (
            $this->input('driver_id') === ''
        ) {
            $data['driver_id'] = null;
        }


        if (
            $this->input('vehicle_id') === ''
        ) {
            $data['vehicle_id'] = null;
        }


        if (
            $this->input('vehicle_type_id') === ''
        ) {
            $data['vehicle_type_id'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | MERGE
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
        return [

            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP INFORMATION
            |--------------------------------------------------------------------------
            */

            /*
            | Server generates the final sequence.
            | The readonly Blade value is only for display.
            */
            'slip_no' => [
                'nullable',
                'string',
                'max:100',
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
            |
            | Manual selection.
            |
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
            |
            | Actual VehicleManagement table:
            | vehicle_management
            |
            */

            'vehicle_id' => [
                'nullable',
                'integer',
                'exists:vehicle_management,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | VEHICLE TYPE
            |--------------------------------------------------------------------------
            |
            | Preferred field:
            | vehicle_type_id
            |
            */

            'vehicle_type_id' => [
                'nullable',
                'integer',
                'exists:vehicle_types,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | LEGACY VEHICLE TYPE
            |--------------------------------------------------------------------------
            |
            | Backward compatibility with older Blade/input.
            |
            */

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

            /*
            |--------------------------------------------------------------------------
            | TOTAL KM
            |--------------------------------------------------------------------------
            |
            | Frontend value is readonly.
            | Backend Controller/Service recalculates it.
            |
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
            */

            'driver_allowances' => [
                'nullable',
                'array',
                'max:50',
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

            /*
            |--------------------------------------------------------------------------
            | RATE
            |--------------------------------------------------------------------------
            |
            | Service uses master Allowance amount.
            | This value is not trusted for calculation.
            |
            */

            'driver_allowances.*.rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | AMOUNT
            |--------------------------------------------------------------------------
            |
            | Service calculates amount server-side.
            |
            */

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
            */

            'driver_expenses' => [
                'nullable',
                'array',
                'max:50',
            ],

            'driver_expenses.*.expense_id' => [
                'nullable',
                'integer',
                'exists:expenses,id',
                'distinct',
            ],

            'driver_expenses.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            /*
            |--------------------------------------------------------------------------
            | RATE
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | AMOUNT
            |--------------------------------------------------------------------------
            */

            'driver_expenses.*.amount' => [
                'nullable',
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
        $validator->after(
            function (Validator $validator) {

                /*
                |--------------------------------------------------------------------------
                | Do Not Continue With Invalid Core Fields
                |--------------------------------------------------------------------------
                */

                if (
                    $validator->errors()->has(
                        'duty_assignment_id'
                    )
                ) {
                    return;
                }


                if (
                    $validator->errors()->has(
                        'driver_id'
                    )
                ) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | SAME-DAY / CROSS-DAY DATETIME VALIDATION
                |--------------------------------------------------------------------------
                |
                | Examples:
                |
                | Valid:
                | 21 Sep 18:00 -> 21 Sep 23:00
                |
                | Valid:
                | 21 Sep 22:00 -> 22 Sep 02:00
                |
                | Invalid:
                | 21 Sep 18:00 -> 21 Sep 09:00
                |
                */

                $startDate =
                    $this->input('start_date')
                    ?: $this->input('duty_date');

                $endDate =
                    $this->input('end_date')
                    ?: $this->input('duty_date');

                $startTime =
                    $this->input('start_time');

                $endTime =
                    $this->input('end_time');


                if (
                    !empty($startDate) &&
                    !empty($endDate) &&
                    !empty($startTime) &&
                    !empty($endTime)
                ) {

                    try {

                        $startDateTime =
                            \Carbon\Carbon::createFromFormat(
                                'Y-m-d H:i',
                                "{$startDate} {$startTime}"
                            );

                        $endDateTime =
                            \Carbon\Carbon::createFromFormat(
                                'Y-m-d H:i',
                                "{$endDate} {$endTime}"
                            );


                        if (
                            $endDateTime->lessThan(
                                $startDateTime
                            )
                        ) {

                            $validator->errors()->add(
                                'end_time',
                                'End date and time cannot be before start date and time.'
                            );
                        }

                    } catch (\Throwable $exception) {

                        /*
                        |--------------------------------------------------------------------------
                        | Basic date/time rules already handle invalid formats.
                        |--------------------------------------------------------------------------
                        */

                    }
                }


                /*
                |--------------------------------------------------------------------------
                | VEHICLE TYPE COMPATIBILITY CHECK
                |--------------------------------------------------------------------------
                |
                | When both modern and legacy fields are submitted, they should
                | resolve to the same Vehicle Type.
                |
                */

                $vehicleTypeId =
                    $this->input('vehicle_type_id');

                $legacyVehicleType =
                    trim(
                        (string) $this->input(
                            'vehicle_type'
                        )
                    );


                if (
                    !empty($vehicleTypeId) &&
                    $legacyVehicleType !== ''
                ) {

                    $legacyResolvedId =
                        null;


                    if (
                        ctype_digit(
                            $legacyVehicleType
                        )
                    ) {

                        $legacyResolvedId =
                            (int) $legacyVehicleType;

                    } else {

                        $legacyResolvedId =
                            \App\Models\VehicleType::query()
                                ->where(
                                    'name',
                                    $legacyVehicleType
                                )
                                ->value('id');


                        if (
                            !$legacyResolvedId
                        ) {

                            $legacyResolvedId =
                                \App\Models\VehicleType::query()
                                    ->where(
                                        'code',
                                        $legacyVehicleType
                                    )
                                    ->value('id');
                        }
                    }


                    if (
                        $legacyResolvedId &&
                        (int) $vehicleTypeId !==
                        (int) $legacyResolvedId
                    ) {

                        $validator->errors()->add(
                            'vehicle_type_id',
                            'Selected vehicle type values do not match.'
                        );
                    }
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOM MESSAGES
    |--------------------------------------------------------------------------
    */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | DUTY SLIP
            |--------------------------------------------------------------------------
            */
            'slip_no.string' =>
                'Duty slip number must be valid text.',

            'slip_no.max' =>
                'Duty slip number may not exceed 100 characters.',


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
            | VEHICLE TYPE
            |--------------------------------------------------------------------------
            */
            'vehicle_type_id.integer' =>
                'Invalid vehicle type selected.',

            'vehicle_type_id.exists' =>
                'Selected vehicle type does not exist.',

            'vehicle_type.string' =>
                'Vehicle type must be valid text.',

            'vehicle_type.max' =>
                'Vehicle type may not exceed 100 characters.',


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
            | START DATE
            |--------------------------------------------------------------------------
            */
            'start_date.date' =>
                'Please enter a valid start date.',


            /*
            |--------------------------------------------------------------------------
            | END DATE
            |--------------------------------------------------------------------------
            */
            'end_date.date' =>
                'Please enter a valid end date.',

            'end_date.after_or_equal' =>
                'End date cannot be before start date.',


            /*
            |--------------------------------------------------------------------------
            | TIME
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
            | ALLOWANCES
            |--------------------------------------------------------------------------
            */
            'driver_allowances.array' =>
                'Driver allowances must be provided in a valid format.',

            'driver_allowances.max' =>
                'Too many driver allowance rows were submitted.',

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

            'driver_allowances.*.status.required' =>
                'Allowance status is required.',

            'driver_allowances.*.status.in' =>
                'Selected allowance status is invalid.',


            /*
            |--------------------------------------------------------------------------
            | EXPENSES
            |--------------------------------------------------------------------------
            */
            'driver_expenses.array' =>
                'Driver expenses must be provided in a valid format.',

            'driver_expenses.max' =>
                'Too many driver expense rows were submitted.',

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

            'driver_expenses.*.rate.numeric' =>
                'Expense rate must be a valid number.',

            'driver_expenses.*.rate.min' =>
                'Expense rate cannot be negative.',

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
            | FILES
            |--------------------------------------------------------------------------
            */

            'duty_slip_front_file.file' =>
                'Please upload a valid duty slip front file.',

            'duty_slip_front_file.mimes' =>
                'Duty slip front file must be a PDF, JPG, JPEG, or PNG file.',

            'duty_slip_front_file.max' =>
                'Duty slip front file may not exceed 5 MB.',

            'duty_slip_back_file.file' =>
                'Please upload a valid duty slip back file.',

            'duty_slip_back_file.mimes' =>
                'Duty slip back file must be a PDF, JPG, JPEG, or PNG file.',

            'duty_slip_back_file.max' =>
                'Duty slip back file may not exceed 5 MB.',


            /*
            |--------------------------------------------------------------------------
            | STATUS
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