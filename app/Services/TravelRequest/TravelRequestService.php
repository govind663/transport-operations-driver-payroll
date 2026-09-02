<?php

namespace App\Services\TravelRequest;

use App\Models\TravelRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TravelRequestService
{
    /*
    |--------------------------------------------------------------------------
    | Eager Loaded Relationships
    |--------------------------------------------------------------------------
    */

    protected array $relations = [
        'createdBy',
        'updatedBy',
        'deletedBy',
        'dutyAssignment',
    ];

    /*
    |--------------------------------------------------------------------------
    | GET TRAVEL REQUESTS
    |--------------------------------------------------------------------------
    */

    public function getTravelRequests(
        int $perPage = 20
    ): LengthAwarePaginator {

        return TravelRequest::query()
            ->with($this->relations)
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND TRAVEL REQUEST
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): TravelRequest {

        return TravelRequest::query()
            ->with($this->relations)
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): TravelRequest {

        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

            $data['created_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Company Name
            |--------------------------------------------------------------------------
            |
            | company_name is a text field.
            | No client_id / Client relation is used anymore.
            |
            */

            if (isset($data['company_name'])) {
                $data['company_name'] = $this->cleanText(
                    $data['company_name']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Requested By
            |--------------------------------------------------------------------------
            |
            | requested_by is the external requester name/string.
            | Do NOT replace it with Auth::id().
            |
            */

            if (isset($data['requested_by'])) {
                $data['requested_by'] = $this->cleanText(
                    $data['requested_by']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Request Number
            |--------------------------------------------------------------------------
            |
            | Generate automatically when request_no is not provided.
            |
            */

            if (empty($data['request_no'])) {

                $data['request_no'] =
                    $this->generateRequestNumber();

            } else {

                $data['request_no'] = strtoupper(
                    trim($data['request_no'])
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Travel Date & Time
            |--------------------------------------------------------------------------
            |
            | DB requires travel_date_time.
            |
            | travel_from_date + pickup_time
            |
            | Example:
            |
            | 2026-09-10 + 09:30
            |
            | becomes:
            |
            | 2026-09-10 09:30:00
            |
            */

            $this->prepareTravelDateTime($data);

            /*
            |--------------------------------------------------------------------------
            | Default Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? TravelRequest::STATUS_PENDING;

            /*
            |--------------------------------------------------------------------------
            | Create Travel Request
            |--------------------------------------------------------------------------
            */

            $travelRequest = TravelRequest::create($data);

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Model With Relationships
            |--------------------------------------------------------------------------
            */

            return $travelRequest->fresh($this->relations);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        TravelRequest $travelRequest,
        array $data
    ): TravelRequest {

        return DB::transaction(function () use (
            $travelRequest,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $data['updated_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Company Name
            |--------------------------------------------------------------------------
            */

            if (array_key_exists('company_name', $data)) {
                $data['company_name'] = $this->cleanText(
                    $data['company_name']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Requested By
            |--------------------------------------------------------------------------
            */

            if (array_key_exists('requested_by', $data)) {
                $data['requested_by'] = $this->cleanText(
                    $data['requested_by']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Request Number
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['request_no']) &&
                !empty($data['request_no'])
            ) {

                $data['request_no'] = strtoupper(
                    trim($data['request_no'])
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Travel Date & Time
            |--------------------------------------------------------------------------
            */

            $this->prepareTravelDateTime(
                $data,
                $travelRequest
            );

            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            $travelRequest->update($data);

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Model
            |--------------------------------------------------------------------------
            */

            return $travelRequest->fresh($this->relations);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        TravelRequest $travelRequest
    ): bool {

        return DB::transaction(function () use (
            $travelRequest
        ) {

            /*
            |--------------------------------------------------------------------------
            | Deleted By
            |--------------------------------------------------------------------------
            */

            $travelRequest->deleted_by = Auth::id();

            $travelRequest->save();

            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            return $travelRequest->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE REQUEST NUMBER
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
    | PREPARE TRAVEL DATE & TIME
    |--------------------------------------------------------------------------
    */

    protected function prepareTravelDateTime(
        array &$data,
        ?TravelRequest $travelRequest = null
    ): void {

        /*
        |--------------------------------------------------------------------------
        | From Date + Pickup Time
        |--------------------------------------------------------------------------
        */

        if (
            !empty($data['travel_from_date']) &&
            !empty($data['pickup_time'])
        ) {

            $date = trim(
                (string) $data['travel_from_date']
            );

            $time = trim(
                (string) $data['pickup_time']
            );

            /*
            | If time is H:i, add seconds.
            | If time is already H:i:s, don't add again.
            */

            if (strlen($time) === 5) {
                $time .= ':00';
            }

            $data['travel_date_time'] =
                $date . ' ' . $time;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Existing travel_date_time Supplied
        |--------------------------------------------------------------------------
        */

        if (!empty($data['travel_date_time'])) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Case
        |--------------------------------------------------------------------------
        |
        | If user edits the request without changing date/time,
        | preserve existing travel_date_time.
        |
        */

        if (
            $travelRequest &&
            $travelRequest->travel_date_time
        ) {

            $data['travel_date_time'] =
                $travelRequest->travel_date_time
                    ->format('Y-m-d H:i:s');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback Using Travel From Date
        |--------------------------------------------------------------------------
        */

        if (!empty($data['travel_from_date'])) {

            $data['travel_date_time'] =
                trim(
                    (string) $data['travel_from_date']
                ) . ' 00:00:00';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Last Fallback
        |--------------------------------------------------------------------------
        */

        $data['travel_date_time'] =
            now()->format('Y-m-d H:i:s');
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN TEXT
    |--------------------------------------------------------------------------
    */

    protected function cleanText(
        mixed $value
    ): ?string {

        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === ''
            ? null
            : $value;
    }
}