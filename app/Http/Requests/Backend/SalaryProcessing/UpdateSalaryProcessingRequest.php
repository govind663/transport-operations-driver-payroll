<?php

namespace App\Http\Requests\Backend\SalaryProcessing;

use App\Models\SalaryProcessing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSalaryProcessingRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Authorize
    |--------------------------------------------------------------------------
    */

    public function authorize(): bool
    {
        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    public function rules(): array
    {
        return [

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

            'gross_salary' => [
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

            'total_deductions' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Net Salary
            |--------------------------------------------------------------------------
            */

            'net_salary' => [
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
                Rule::in(
                    SalaryProcessing::STATUSES
                ),
            ],


            /*
            |--------------------------------------------------------------------------
            | Payment Date
            |--------------------------------------------------------------------------
            */

            'payment_date' => [
                'nullable',
                'date',
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


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

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
            | Salary Period
            |--------------------------------------------------------------------------
            */

            'salary_month.required' =>
                'Salary month is required.',

            'salary_month.integer' =>
                'Salary month must be a valid number.',

            'salary_month.between' =>
                'Salary month must be between 1 and 12.',

            'salary_year.required' =>
                'Salary year is required.',

            'salary_year.integer' =>
                'Salary year must be a valid number.',

            'salary_year.min' =>
                'Salary year must be 2000 or later.',

            'salary_year.max' =>
                'Salary year cannot be greater than 2100.',


            /*
            |--------------------------------------------------------------------------
            | Attendance / Working
            |--------------------------------------------------------------------------
            */

            'total_working_days.numeric' =>
                'Total working days must be a valid number.',

            'present_days.numeric' =>
                'Present days must be a valid number.',

            'absent_days.numeric' =>
                'Absent days must be a valid number.',

            'paid_days.numeric' =>
                'Paid days must be a valid number.',

            'overtime_hours.numeric' =>
                'Overtime hours must be a valid number.',


            /*
            |--------------------------------------------------------------------------
            | Earnings
            |--------------------------------------------------------------------------
            */

            'basic_salary.required' =>
                'Basic salary is required.',

            'basic_salary.numeric' =>
                'Basic salary must be a valid amount.',

            'allowance_amount.numeric' =>
                'Allowance amount must be a valid amount.',

            'overtime_amount.numeric' =>
                'Overtime amount must be a valid amount.',

            'bonus_amount.numeric' =>
                'Bonus amount must be a valid amount.',

            'other_earnings.numeric' =>
                'Other earnings must be a valid amount.',

            'gross_salary.numeric' =>
                'Gross salary must be a valid amount.',


            /*
            |--------------------------------------------------------------------------
            | Deductions
            |--------------------------------------------------------------------------
            */

            'advance_deduction.numeric' =>
                'Advance deduction must be a valid amount.',

            'loan_deduction.numeric' =>
                'Loan deduction must be a valid amount.',

            'penalty_deduction.numeric' =>
                'Penalty deduction must be a valid amount.',

            'other_deductions.numeric' =>
                'Other deductions must be a valid amount.',

            'total_deductions.numeric' =>
                'Total deductions must be a valid amount.',


            /*
            |--------------------------------------------------------------------------
            | Net Salary
            |--------------------------------------------------------------------------
            */

            'net_salary.numeric' =>
                'Net salary must be a valid amount.',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status.required' =>
                'Salary processing status is required.',

            'status.in' =>
                'Invalid salary processing status.',


            /*
            |--------------------------------------------------------------------------
            | Payment Date
            |--------------------------------------------------------------------------
            */

            'payment_date.date' =>
                'Payment date must be a valid date.',


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks.string' =>
                'Remarks must be a valid text.',

            'remarks.max' =>
                'Remarks cannot exceed 2000 characters.',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Data
    |--------------------------------------------------------------------------
    */

    protected function prepareForValidation(): void
    {
        $this->merge([

            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id' =>
                $this->driver_id !== null
                    ? (int) $this->driver_id
                    : null,


            /*
            |--------------------------------------------------------------------------
            | Salary Period
            |--------------------------------------------------------------------------
            */

            'salary_month' =>
                $this->salary_month !== null
                    ? (int) $this->salary_month
                    : null,

            'salary_year' =>
                $this->salary_year !== null
                    ? (int) $this->salary_year
                    : null,

        ]);
    }
}