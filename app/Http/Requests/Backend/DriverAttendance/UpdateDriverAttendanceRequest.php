<?php

namespace App\Http\Requests\Backend\DriverAttendance;

use App\Models\DriverAttendance;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDriverAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('web')->check();
    }

    /**
     * Prepare and normalize request data before validation.
     */
    protected function prepareForValidation(): void
    {
        $inTime = $this->normalizeTime(
            $this->input('in_time')
        );

        $outTime = $this->normalizeTime(
            $this->input('out_time')
        );

        $totalHours = $this->normalizeHours(
            $this->input('total_hours')
        );

        /*
         * Calculate total hours from actual time values.
         * Earlier Out Time indicates an overnight shift.
         */
        if (
            $this->isValidNormalizedTime($inTime) &&
            $this->isValidNormalizedTime($outTime)
        ) {
            $totalHours = $this->calculateTotalHours(
                $inTime,
                $outTime
            );
        }

        $this->merge([
            'status' => $this->normalizeStatus(
                $this->input('status')
            ),

            'in_time' => $inTime,
            'out_time' => $outTime,
            'total_hours' => $totalHours,
        ]);
    }

    /**
     * Normalize attendance status.
     *
     * Present       -> present
     * Half Day      -> half_day
     * Present Late  -> present_late
     * Early-Exit    -> early_exit
     */
    private function normalizeStatus(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return preg_replace(
            '/[\s-]+/',
            '_',
            strtolower($value)
        );
    }

    /**
     * Normalize time to H:i:s.
     *
     * Accepted formats:
     * 09:00
     * 09:00:00
     * 09:00 AM
     * 06:00 PM
     */
    private function normalizeTime(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $formats = [
            'H:i:s',
            'H:i',
            'h:i A',
            'h:i:s A',
            'h:i a',
            'h:i:s a',
        ];

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat(
                '!' . $format,
                $value
            );

            if (
                $date !== false &&
                $date->format($format) === $value
            ) {
                return $date->format('H:i:s');
            }
        }

        return $value;
    }

    /**
     * Check whether normalized time is valid.
     */
    private function isValidNormalizedTime(?string $value): bool
    {
        if ($value === null) {
            return false;
        }

        $date = DateTime::createFromFormat(
            '!H:i:s',
            $value
        );

        return $date !== false &&
            $date->format('H:i:s') === $value;
    }

    /**
     * Calculate total hours.
     *
     * Supports overnight attendance.
     *
     * Example:
     * In Time  : 09:30 AM
     * Out Time : 06:30 AM (next day)
     * Total    : 21.00 hours
     */
    private function calculateTotalHours(
        string $inTime,
        string $outTime
    ): string {

        [$inHours, $inMinutes] = array_map(
            'intval',
            array_slice(explode(':', $inTime), 0, 2)
        );

        [$outHours, $outMinutes] = array_map(
            'intval',
            array_slice(explode(':', $outTime), 0, 2)
        );

        $inTotalMinutes =
            ($inHours * 60) + $inMinutes;

        $outTotalMinutes =
            ($outHours * 60) + $outMinutes;

        /*
         * Earlier Out Time means the next calendar day.
         */
        $durationMinutes =
            ($outTotalMinutes - $inTotalMinutes + 1440) % 1440;

        return number_format(
            $durationMinutes / 60,
            2,
            '.',
            ''
        );
    }

    /**
     * Normalize hours into decimal format.
     *
     * 09:00 -> 9.00
     * 09:30 -> 9.50
     * 21:00 -> 21.00
     */
    private function normalizeHours(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (
            preg_match(
                '/^(\d{1,3}):([0-5]\d)$/',
                $value,
                $matches
            )
        ) {
            return number_format(
                (int) $matches[1] + ((int) $matches[2] / 60),
                2,
                '.',
                ''
            );
        }

        return $value;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'driver_id' => [
                'required',
                'integer',
                Rule::exists('drivers', 'id'),
            ],

            'attendance_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'string',
                Rule::in(DriverAttendance::STATUSES),
            ],

            'in_time' => [
                'nullable',
                'date_format:H:i:s',
            ],

            'out_time' => [
                'nullable',
                'date_format:H:i:s',
            ],

            'total_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Driver validation
            'driver_id.required' =>
                'Please select a driver.',

            'driver_id.integer' =>
                'Please select a valid driver.',

            'driver_id.exists' =>
                'The selected driver does not exist.',

            // Attendance date validation
            'attendance_date.required' =>
                'Please select an attendance date.',

            'attendance_date.date' =>
                'Please enter a valid attendance date.',

            // Status validation
            'status.required' =>
                'Please select an attendance status.',

            'status.string' =>
                'Attendance status must be valid.',

            'status.in' =>
                'The selected attendance status is invalid.',

            // In Time validation
            'in_time.date_format' =>
                'Please enter a valid In Time.',

            // Out Time validation
            'out_time.date_format' =>
                'Please enter a valid Out Time.',

            // Total Hours validation
            'total_hours.numeric' =>
                'Total Hours must be a valid number.',

            'total_hours.min' =>
                'Total Hours cannot be negative.',

            'total_hours.max' =>
                'Total Hours cannot exceed 999.99.',

            // Remarks validation
            'remarks.string' =>
                'Remarks must be valid text.',

            'remarks.max' =>
                'Remarks cannot exceed 1000 characters.',
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'driver_id' => 'driver',
            'attendance_date' => 'attendance date',
            'status' => 'attendance status',
            'in_time' => 'in time',
            'out_time' => 'out time',
            'total_hours' => 'total hours',
            'remarks' => 'remarks',
        ];
    }
}
