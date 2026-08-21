<?php

namespace App\Http\Requests\Backend\SalarySlip;

use App\Models\SalarySlip;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSalarySlipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $salarySlip = $this->route('salarySlip');

        $salarySlipId = $salarySlip instanceof SalarySlip
            ? $salarySlip->id
            : $salarySlip;

        return [

            /*
            |--------------------------------------------------------------------------
            | Salary Processing
            |--------------------------------------------------------------------------
            */

            'salary_processing_id' => [
                'required',
                'integer',
                'exists:salary_processings,id',
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
            | Role
            |--------------------------------------------------------------------------
            */

            'role' => [
                'nullable',
                Rule::in(SalarySlip::ROLES),
            ],

            /*
            |--------------------------------------------------------------------------
            | Slip Number
            |--------------------------------------------------------------------------
            */

            'slip_no' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(
                    'salary_slips',
                    'slip_no'
                )->ignore($salarySlipId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Salary Period
            |--------------------------------------------------------------------------
            */

            'salary_month' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'salary_year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'period_from' => [
                'nullable',
                'date',
            ],

            'period_to' => [
                'nullable',
                'date',
                'after_or_equal:period_from',
            ],

            /*
            |--------------------------------------------------------------------------
            | Attendance / Working
            |--------------------------------------------------------------------------
            */

            'total_working_days' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'present_days' => [
                'nullable',
                'numeric',
                'min:0',
                'lte:total_working_days',
            ],

            'absent_days' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_days' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'overtime_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Earnings
            |--------------------------------------------------------------------------
            */

            'basic_salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'allowance_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'overtime_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'bonus_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_earnings' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Deductions
            |--------------------------------------------------------------------------
            */

            'advance_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'loan_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'penalty_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deductions' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Calculated Salary
            |--------------------------------------------------------------------------
            */

            'gross_salary' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_deductions' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'net_salary' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_date' => [
                'nullable',
                'date',
            ],

            'payment_status' => [
                'nullable',
                Rule::in(SalarySlip::PAYMENT_STATUSES),
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'nullable',
                Rule::in(SalarySlip::STATUSES),
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
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            'salary_processing_id.required' =>
                'Salary processing record is required.',

            'salary_processing_id.exists' =>
                'Selected salary processing record does not exist.',

            'driver_id.required' =>
                'Please select a driver.',

            'driver_id.exists' =>
                'Selected driver does not exist.',

            'salary_month.required' =>
                'Salary month is required.',

            'salary_month.between' =>
                'Salary month must be between 1 and 12.',

            'salary_year.required' =>
                'Salary year is required.',

            'period_to.after_or_equal' =>
                'Salary period end date must be after or equal to start date.',

            'basic_salary.required' =>
                'Basic salary is required.',

            'present_days.lte' =>
                'Present days cannot be greater than total working days.',

            'payment_status.in' =>
                'Invalid payment status.',

            'status.in' =>
                'Invalid salary slip status.',
        ];
    }

    /**
     * Prepare Request Data
     */
    protected function prepareForValidation(): void
    {
        $this->merge([

            'salary_processing_id' =>
                $this->salary_processing_id !== null
                    ? (int) $this->salary_processing_id
                    : null,

            'driver_id' =>
                $this->driver_id !== null
                    ? (int) $this->driver_id
                    : null,

            'salary_month' =>
                $this->salary_month !== null
                    ? (int) $this->salary_month
                    : null,

            'salary_year' =>
                $this->salary_year !== null
                    ? (int) $this->salary_year
                    : null,

            'total_working_days' =>
                $this->total_working_days !== null
                    ? (float) $this->total_working_days
                    : null,

            'present_days' =>
                $this->present_days !== null
                    ? (float) $this->present_days
                    : null,

            'absent_days' =>
                $this->absent_days !== null
                    ? (float) $this->absent_days
                    : null,

            'paid_days' =>
                $this->paid_days !== null
                    ? (float) $this->paid_days
                    : null,

            'overtime_hours' =>
                $this->overtime_hours !== null
                    ? (float) $this->overtime_hours
                    : null,
        ]);
    }
}