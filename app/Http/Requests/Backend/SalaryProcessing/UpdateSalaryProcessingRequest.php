<?php

namespace App\Http\Requests\Backend\SalaryProcessing;

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
        // Actual role and permission checks must also be enforced
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
            | Payroll Status
            |--------------------------------------------------------------------------
            |
            | Status dropdown is available for editable payroll.
            | Locked payroll displays a readonly input without a name,
            | so status is allowed to be absent in that case.
            |
            */

            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    'draft',
                    'processed',
                    'approved',
                    'paid',
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

            'total_working_days.min' =>
                'Total working days cannot be negative.',

            'present_days.required' =>
                'Present days are required.',

            'present_days.numeric' =>
                'Present days must be a valid number.',

            'present_days.min' =>
                'Present days cannot be negative.',

            'absent_days.required' =>
                'Absent days are required.',

            'absent_days.numeric' =>
                'Absent days must be a valid number.',

            'absent_days.min' =>
                'Absent days cannot be negative.',

            'paid_days.required' =>
                'Paid days are required.',

            'paid_days.numeric' =>
                'Paid days must be a valid number.',

            'paid_days.min' =>
                'Paid days cannot be negative.',

            'overtime_hours.numeric' =>
                'Overtime hours must be a valid number.',

            'overtime_hours.min' =>
                'Overtime hours cannot be negative.',

            'total_duty_days.numeric' =>
                'Total duty days must be a valid number.',

            'total_duty_days.min' =>
                'Total duty days cannot be negative.',

            'total_km.numeric' =>
                'Total kilometres must be a valid number.',

            'total_km.min' =>
                'Total kilometres cannot be negative.',

            // Earnings
            'basic_salary.required' =>
                'Basic salary is required.',

            'basic_salary.numeric' =>
                'Basic salary must be a valid amount.',

            'basic_salary.min' =>
                'Basic salary cannot be negative.',

            'working_sheet_base_amount.numeric' =>
                'Working Sheet base amount must be a valid amount.',

            'working_sheet_base_amount.min' =>
                'Working Sheet base amount cannot be negative.',

            'extra_km_amount.numeric' =>
                'Extra KM amount must be a valid amount.',

            'extra_km_amount.min' =>
                'Extra KM amount cannot be negative.',

            'allowance_amount.numeric' =>
                'Allowance amount must be a valid amount.',

            'allowance_amount.min' =>
                'Allowance amount cannot be negative.',

            'overtime_amount.numeric' =>
                'Overtime amount must be a valid amount.',

            'overtime_amount.min' =>
                'Overtime amount cannot be negative.',

            'expense_reimbursement_amount.numeric' =>
                'Expense reimbursement must be a valid amount.',

            'expense_reimbursement_amount.min' =>
                'Expense reimbursement cannot be negative.',

            'working_sheet_other_amount.numeric' =>
                'Working Sheet other amount must be a valid amount.',

            'working_sheet_other_amount.min' =>
                'Working Sheet other amount cannot be negative.',

            'bonus_amount.numeric' =>
                'Bonus must be a valid amount.',

            'bonus_amount.min' =>
                'Bonus cannot be negative.',

            'other_earnings.numeric' =>
                'Other earnings must be a valid amount.',

            'other_earnings.min' =>
                'Other earnings cannot be negative.',

            // Deductions
            'advance_deduction.numeric' =>
                'Advance deduction must be a valid amount.',

            'advance_deduction.min' =>
                'Advance deduction cannot be negative.',

            'loan_deduction.numeric' =>
                'Loan deduction must be a valid amount.',

            'loan_deduction.min' =>
                'Loan deduction cannot be negative.',

            'penalty_deduction.numeric' =>
                'Penalty deduction must be a valid amount.',

            'penalty_deduction.min' =>
                'Penalty deduction cannot be negative.',

            'other_deductions.numeric' =>
                'Other deductions must be a valid amount.',

            'other_deductions.min' =>
                'Other deductions cannot be negative.',

            // Payroll Status
            'status.required' =>
                'Please select a payroll status.',

            'status.in' =>
                'Please select a valid payroll status.',

            // Remarks
            'remarks.string' =>
                'Remarks must be valid text.',

            'remarks.max' =>
                'Remarks cannot exceed 2000 characters.',

        ];
    }
}
