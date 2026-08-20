<?php

namespace App\Http\Requests\Backend\DriverAttendance;

use App\Models\DriverAttendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreDriverAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('web')->check();
    }


    public function rules(): array
    {
        return [

            'driver_id' => [
                'required',
                'integer',
                'exists:drivers,id',
            ],

            'attendance_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                Rule::in(
                    DriverAttendance::STATUSES
                ),
            ],

            'in_time' => [
                'nullable',
                'date_format:H:i:s',
            ],

            'out_time' => [
                'nullable',
                'date_format:H:i:s',
                'after_or_equal:in_time',
            ],

            'total_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }
}