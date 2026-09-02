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
        'client',
        'requestedBy',
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
            | Requested By
            |--------------------------------------------------------------------------
            */

            if (empty($data['requested_by'])) {
                $data['requested_by'] = Auth::id();
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
                $data['request_no'] = $this->generateRequestNumber();
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
            | Reference form provides:
            |
            | travel_from_date + pickup_time
            |
            | Example:
            | 2026-09-10 + 09:30
            |
            | becomes:
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

            $data['travel_date_time'] =
                $data['travel_from_date'] .
                ' ' .
                $data['pickup_time'] .
                ':00';

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
        | Final Fallback
        |--------------------------------------------------------------------------
        |
        | This prevents NOT NULL database errors.
        |
        */

        if (!empty($data['travel_from_date'])) {

            $data['travel_date_time'] =
                $data['travel_from_date'] .
                ' 00:00:00';

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
}