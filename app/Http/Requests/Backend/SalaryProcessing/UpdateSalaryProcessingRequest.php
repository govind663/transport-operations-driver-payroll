<?php

namespace App\Http\Requests\Backend\SalaryProcessing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryProcessingRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Authorize
    |--------------------------------------------------------------------------
    */

    public function authorize(): bool
    {
        // Actual role/permission checks must also be enforced
        // in Controller and Service.
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
            | Attendance / Working
            |--------------------------------------------------------------------------
            */

            'total_working_days' => [
                'required',
                'numeric',
                'min:0',
            ],

            'present_days' => [
                'required',
                'numeric',
                'min:0',
            ],

            'absent_days' => [
                'required',
                'numeric',
                'min:0',
            ],

            'paid_days' => [
                'required',
                'numeric',
                'min:0',
            ],

            'overtime_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_duty_days' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_km' => [
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

            'working_sheet_base_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'extra_km_amount' => [
                'nullable',
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

            'expense_reimbursement_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'working_sheet_other_amount' => [
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

            // Attendance
            'total_working_days.required' =>
                'Total working days are required.',

            'total_working_days.numeric' =>
                'Total working days must be a valid number.',

            'present_days.required' =>
                'Present days are required.',

            'present_days.numeric' =>
                'Present days must be a valid number.',

            'absent_days.required' =>
                'Absent days are required.',

            'absent_days.numeric' =>
                'Absent days must be a valid number.',

            'paid_days.required' =>
                'Paid days are required.',

            'paid_days.numeric' =>
                'Paid days must be a valid number.',

            'overtime_hours.numeric' =>
                'Overtime hours must be a valid number.',

            'total_duty_days.numeric' =>
                'Total duty days must be a valid number.',

            'total_km.numeric' =>
                'Total kilometres must be a valid number.',

            // Earnings
            'basic_salary.required' =>
                'Basic salary is required.',

            'basic_salary.numeric' =>
                'Basic salary must be a valid amount.',

            'working_sheet_base_amount.numeric' =>
                'Working Sheet base amount must be a valid amount.',

            'extra_km_amount.numeric' =>
                'Extra KM amount must be a valid amount.',

            'allowance_amount.numeric' =>
                'Allowance amount must be a valid amount.',

            'overtime_amount.numeric' =>
                'Overtime amount must be a valid amount.',

            'expense_reimbursement_amount.numeric' =>
                'Expense reimbursement must be a valid amount.',

            'working_sheet_other_amount.numeric' =>
                'Working Sheet other amount must be a valid amount.',

            'bonus_amount.numeric' =>
                'Bonus must be a valid amount.',

            'other_earnings.numeric' =>
                'Other earnings must be a valid amount.',

            // Deductions
            'advance_deduction.numeric' =>
                'Advance deduction must be a valid amount.',

            'loan_deduction.numeric' =>
                'Loan deduction must be a valid amount.',

            'penalty_deduction.numeric' =>
                'Penalty deduction must be a valid amount.',

            'other_deductions.numeric' =>
                'Other deductions must be a valid amount.',

            // Remarks
            'remarks.string' =>
                'Remarks must be valid text.',

            'remarks.max' =>
                'Remarks cannot exceed 2000 characters.',

        ];
    }
}
