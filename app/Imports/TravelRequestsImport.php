<?php

namespace App\Imports;

use App\Models\TravelRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TravelRequestsImport implements ToCollection, WithHeadingRow
{
    /**
     * Import Excel rows into travel_requests table.
     *
     * @param Collection<int, Collection<string, mixed>> $rows
     */
    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {

                /*
                |--------------------------------------------------------------------------
                | Convert Row To Array
                |--------------------------------------------------------------------------
                */

                $data = $row->toArray();


                /*
                |--------------------------------------------------------------------------
                | Skip Empty Rows
                |--------------------------------------------------------------------------
                */

                if (
                    empty($data['request_no']) &&
                    empty($data['company_name']) &&
                    empty($data['passenger_name'])
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Request Number
                |--------------------------------------------------------------------------
                */

                $requestNo = !empty($data['request_no'])
                    ? strtoupper(trim((string) $data['request_no']))
                    : $this->generateRequestNumber();


                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Request Number
                |--------------------------------------------------------------------------
                */

                if (
                    TravelRequest::withTrashed()
                        ->where('request_no', $requestNo)
                        ->exists()
                ) {
                    $requestNo = $this->generateRequestNumber();
                }


                /*
                |--------------------------------------------------------------------------
                | Travel Date Time
                |--------------------------------------------------------------------------
                */

                $travelDateTime = $this->prepareTravelDateTime(
                    $data['travel_date_time'] ?? null,
                    $data['travel_from_date'] ?? null,
                    $data['pickup_time'] ?? null
                );


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                $status = !empty($data['status'])
                    ? strtolower(trim((string) $data['status']))
                    : TravelRequest::STATUS_PENDING;


                $allowedStatuses = [
                    TravelRequest::STATUS_PENDING,
                    TravelRequest::STATUS_APPROVED,
                    TravelRequest::STATUS_REJECTED,
                    TravelRequest::STATUS_ASSIGNED,
                    TravelRequest::STATUS_COMPLETED,
                    TravelRequest::STATUS_CANCELLED,
                ];


                if (!in_array($status, $allowedStatuses, true)) {
                    $status = TravelRequest::STATUS_PENDING;
                }


                /*
                |--------------------------------------------------------------------------
                | Create Travel Request
                |--------------------------------------------------------------------------
                */

                TravelRequest::create([

                    /*
                    |--------------------------------------------------------------------------
                    | Request Information
                    |--------------------------------------------------------------------------
                    */

                    'request_no' => $requestNo,

                    'company_name' => $this->cleanText(
                        $data['company_name'] ?? null
                    ),

                    'requested_by' => $this->cleanText(
                        $data['requested_by'] ?? null
                    ),

                    'employee_email' => $this->cleanText(
                        $data['employee_email'] ?? null
                    ),

                    'travel_id' => $this->cleanText(
                        $data['travel_id'] ?? null
                    ),

                    'trip_id' => $this->cleanText(
                        $data['trip_id'] ?? null
                    ),

                    'vendor_name' => $this->cleanText(
                        $data['vendor_name'] ?? null
                    ),

                    'vehicle_type' => $this->cleanText(
                        $data['vehicle_type'] ?? null
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | Travel Dates
                    |--------------------------------------------------------------------------
                    */

                    'travel_from_date' => $this->prepareDate(
                        $data['travel_from_date'] ?? null
                    ),

                    'travel_to_date' => $this->prepareDate(
                        $data['travel_to_date'] ?? null
                    ),

                    'pickup_time' => $this->prepareTime(
                        $data['pickup_time'] ?? null
                    ),

                    'travel_date_time' => $travelDateTime,


                    /*
                    |--------------------------------------------------------------------------
                    | Locations
                    |--------------------------------------------------------------------------
                    */

                    'from_city' => $this->cleanText(
                        $data['from_city'] ?? null
                    ),

                    'pickup_location' => $this->cleanText(
                        $data['pickup_location'] ?? null
                    ),

                    'drop_location' => $this->cleanText(
                        $data['drop_location'] ?? null
                    ),

                    'release_location' => $this->cleanText(
                        $data['release_location'] ?? null
                    ),

                    'reporting_address' => $this->cleanText(
                        $data['reporting_address'] ?? null
                    ),

                    'release_address' => $this->cleanText(
                        $data['release_address'] ?? null
                    ),

                    'release_time' => $this->prepareTime(
                        $data['release_time'] ?? null
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | Passenger Information
                    |--------------------------------------------------------------------------
                    */

                    'passenger_name' => $this->cleanText(
                        $data['passenger_name'] ?? null
                    ),

                    'passenger_phone' => $this->cleanText(
                        $data['passenger_phone'] ?? null
                    ),

                    'traveler_mobile' => $this->cleanText(
                        $data['traveler_mobile'] ?? null
                    ),

                    'passenger_count' => $this->preparePassengerCount(
                        $data['passenger_count'] ?? null
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | Employee Information
                    |--------------------------------------------------------------------------
                    */

                    'employee_id' => $this->cleanText(
                        $data['employee_id'] ?? null
                    ),

                    'cost_center' => $this->cleanText(
                        $data['cost_center'] ?? null
                    ),

                    'car_hire_type' => $this->cleanText(
                        $data['car_hire_type'] ?? null
                    ),

                    'for_use' => $this->cleanText(
                        $data['for_use'] ?? null
                    ),

                    'gst_number' => $this->cleanText(
                        $data['gst_number'] ?? null
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | Purpose / Instructions
                    |--------------------------------------------------------------------------
                    */

                    'purpose' => $this->cleanText(
                        $data['purpose'] ?? null
                    ),

                    'specific_instruction' => $this->cleanText(
                        $data['specific_instruction'] ?? null
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | Status / Remarks
                    |--------------------------------------------------------------------------
                    */

                    'status' => $status,

                    'remarks' => $this->cleanText(
                        $data['remarks'] ?? null
                    ),


                    /*
                    |--------------------------------------------------------------------------
                    | Audit
                    |--------------------------------------------------------------------------
                    */

                    'created_by' => Auth::id(),

                ]);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Request Number
    |--------------------------------------------------------------------------
    */

    protected function generateRequestNumber(): string
    {
        do {

            $requestNo =
                'TRV-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(random_bytes(4)),
                        0,
                        6
                    )
                );

        } while (
            TravelRequest::withTrashed()
                ->where('request_no', $requestNo)
                ->exists()
        );


        return $requestNo;
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Travel Date Time
    |--------------------------------------------------------------------------
    */

    protected function prepareTravelDateTime(
        mixed $travelDateTime,
        mixed $travelFromDate,
        mixed $pickupTime
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Existing travel_date_time
        |--------------------------------------------------------------------------
        */

        if (!empty($travelDateTime)) {

            try {

                return $this->parseExcelDateTime(
                    $travelDateTime
                )->format('Y-m-d H:i:s');

            } catch (\Throwable $e) {
                // Continue with fallback.
            }
        }


        /*
        |--------------------------------------------------------------------------
        | travel_from_date + pickup_time
        |--------------------------------------------------------------------------
        */

        if (
            !empty($travelFromDate) &&
            !empty($pickupTime)
        ) {

            try {

                $date = $this->parseExcelDate(
                    $travelFromDate
                );

                $time = $this->parseExcelTime(
                    $pickupTime
                );

                return Carbon::parse(
                    $date . ' ' . $time
                )->format('Y-m-d H:i:s');

            } catch (\Throwable $e) {
                // Continue with fallback.
            }
        }


        /*
        |--------------------------------------------------------------------------
        | travel_from_date only
        |--------------------------------------------------------------------------
        */

        if (!empty($travelFromDate)) {

            try {

                return $this->parseExcelDate(
                    $travelFromDate
                ) . ' 00:00:00';

            } catch (\Throwable $e) {
                // Continue with default.
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        return now()->format('Y-m-d H:i:s');
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Date
    |--------------------------------------------------------------------------
    */

    protected function prepareDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }


        try {

            return $this->parseExcelDate($value);

        } catch (\Throwable $e) {

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Parse Excel Date
    |--------------------------------------------------------------------------
    */

    protected function parseExcelDate(mixed $value): string
    {
        /*
        |--------------------------------------------------------------------------
        | Excel Serial Date
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric($value) &&
            (float) $value > 0
        ) {

            return Carbon::create(
                1899,
                12,
                30
            )
                ->addDays((int) floor((float) $value))
                ->format('Y-m-d');
        }


        /*
        |--------------------------------------------------------------------------
        | Normal Date String
        |--------------------------------------------------------------------------
        */

        return Carbon::parse(
            trim((string) $value)
        )->format('Y-m-d');
    }


    /*
    |--------------------------------------------------------------------------
    | Parse Excel DateTime
    |--------------------------------------------------------------------------
    */

    protected function parseExcelDateTime(mixed $value): Carbon
    {
        /*
        |--------------------------------------------------------------------------
        | Excel Serial DateTime
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric($value) &&
            (float) $value > 0
        ) {

            return Carbon::create(
                1899,
                12,
                30
            )->addSeconds(
                (int) round(
                    ((float) $value) * 86400
                )
            );
        }


        return Carbon::parse(
            trim((string) $value)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Time
    |--------------------------------------------------------------------------
    */

    protected function prepareTime(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }


        try {

            return $this->parseExcelTime($value);

        } catch (\Throwable $e) {

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Parse Excel Time
    |--------------------------------------------------------------------------
    */

    protected function parseExcelTime(mixed $value): string
    {
        /*
        |--------------------------------------------------------------------------
        | Excel Time Fraction
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric($value) &&
            (float) $value >= 0 &&
            (float) $value < 1
        ) {

            $seconds = (int) round(
                ((float) $value) * 86400
            );


            // Keep within 24 hours.
            $seconds = $seconds % 86400;


            return gmdate(
                'H:i:s',
                $seconds
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Normal Time String
        |--------------------------------------------------------------------------
        */

        return Carbon::parse(
            trim((string) $value)
        )->format('H:i:s');
    }


    /*
    |--------------------------------------------------------------------------
    | Passenger Count
    |--------------------------------------------------------------------------
    */

    protected function preparePassengerCount(mixed $value): int
    {
        if (
            $value === null ||
            $value === ''
        ) {
            return 1;
        }


        $value = (int) $value;


        if ($value < 1) {
            return 1;
        }


        return min(
            $value,
            1000
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Clean Text
    |--------------------------------------------------------------------------
    */

    protected function cleanText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }


        $value = trim(
            (string) $value
        );


        return $value === ''
            ? null
            : $value;
    }
}