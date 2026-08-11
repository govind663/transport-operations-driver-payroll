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
    | GET TRAVEL REQUESTS
    |--------------------------------------------------------------------------
    */

    public function getTravelRequests(
        int $perPage = 20
    ): LengthAwarePaginator {

        return TravelRequest::query()
            ->with([
                'client',
                'requestedBy',
                'createdBy',
                'updatedBy',
                'dutyAssignment',
            ])
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
            ->with([
                'client',
                'requestedBy',
                'createdBy',
                'updatedBy',
                'dutyAssignment',
            ])
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
            | Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? TravelRequest::STATUS_PENDING;

            return TravelRequest::create($data);
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

            $data['updated_by'] = Auth::id();

            $travelRequest->update($data);

            return $travelRequest->fresh([
                'client',
                'requestedBy',
                'createdBy',
                'updatedBy',
                'dutyAssignment',
            ]);
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

            $travelRequest->deleted_by = Auth::id();

            $travelRequest->save();

            return $travelRequest->delete();
        });
    }
}