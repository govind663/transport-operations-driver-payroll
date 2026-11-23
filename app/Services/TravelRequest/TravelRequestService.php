<?php

namespace App\Services\TravelRequest;

use App\Models\Driver;
use App\Models\User;
use App\Models\TravelRequest;
use App\Models\VehicleManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
        'dutyAssignment.driver',
        'dutyAssignment.vehicle',
    ];

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED USER
    |--------------------------------------------------------------------------
    */

    protected function authenticatedUser(): User
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthenticated access.'
        );

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT ACCESS
    |--------------------------------------------------------------------------
    */

    protected function isManagementUser(User $user): bool
    {
        return $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSIBLE TRAVEL REQUESTS QUERY
    |--------------------------------------------------------------------------
    */

    protected function accessibleTravelRequestsQuery(): Builder
    {
        $user = $this->authenticatedUser();

        $query = TravelRequest::query()
            ->with($this->relations);

        /*
        |--------------------------------------------------------------------------
        | Management Access
        |--------------------------------------------------------------------------
        */

        if ($this->isManagementUser($user)) {
            return $query;
        }

        /*
        |--------------------------------------------------------------------------
        | Driver Access
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = $user->driver;

            abort_unless(
                $driver instanceof Driver,
                403,
                'Driver profile not found.'
            );

            return $query->whereHas(
                'dutyAssignment',
                function (Builder $assignmentQuery) use ($driver) {
                    $assignmentQuery->where(
                        'driver_id',
                        $driver->id
                    );
                }
            );
        }

        abort(403, 'Unauthorized access.');
    }

    /*
    |--------------------------------------------------------------------------
    | GET TRAVEL REQUESTS
    |--------------------------------------------------------------------------
    */

    public function getTravelRequests(
        array $filters = []
    ): Collection {

        $user = $this->authenticatedUser();

        $query = $this->accessibleTravelRequestsQuery();

        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $searchQuery) use ($search) {

                $searchQuery
                    ->where('request_no', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('requested_by', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Driver Filter - Management Only
        |--------------------------------------------------------------------------
        */

        if (
            $this->isManagementUser($user) &&
            !empty($filters['driver_id'])
        ) {

            $query->whereHas(
                'dutyAssignment',
                function (Builder $assignmentQuery) use ($filters) {

                    $assignmentQuery->where(
                        'driver_id',
                        $filters['driver_id']
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Vehicle Filter - Management Only
        |--------------------------------------------------------------------------
        */

        if (
            $this->isManagementUser($user) &&
            !empty($filters['vehicle_id'])
        ) {

            $query->whereHas(
                'dutyAssignment',
                function (Builder $assignmentQuery) use ($filters) {

                    $assignmentQuery->where(
                        'vehicle_id',
                        $filters['vehicle_id']
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Travel Date From
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'travel_date_time',
                '>=',
                $filters['date_from']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Travel Date To
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'travel_date_time',
                '<=',
                $filters['date_to']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Return Results
        |--------------------------------------------------------------------------
        */

        return $query
            ->latest('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | GET FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    public function getFilterOptions(): array
    {
        $user = $this->authenticatedUser();

        $drivers = collect();
        $vehicles = collect();

        /*
        |--------------------------------------------------------------------------
        | Management Filter Options
        |--------------------------------------------------------------------------
        */

        if ($this->isManagementUser($user)) {

            $drivers = Driver::query()
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            $vehicles = VehicleManagement::query()
                ->orderBy('id')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Driver Filter Options
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = $user->driver;

            abort_unless(
                $driver instanceof Driver,
                403,
                'Driver profile not found.'
            );

            $drivers = collect([$driver]);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Options
        |--------------------------------------------------------------------------
        */

        $statuses = TravelRequest::query()
            ->whereNotNull('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        return [
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'statuses' => $statuses,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FIND TRAVEL REQUEST
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): TravelRequest {

        return $this->accessibleTravelRequestsQuery()
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

            if (isset($data['company_name'])) {
                $data['company_name'] = $this->cleanText(
                    $data['company_name']
                );
            }

            if (isset($data['requested_by'])) {
                $data['requested_by'] = $this->cleanText(
                    $data['requested_by']
                );
            }

            if (empty($data['request_no'])) {

                $data['request_no'] =
                    $this->generateRequestNumber();

            } else {

                $data['request_no'] = strtoupper(
                    trim($data['request_no'])
                );
            }

            $this->prepareTravelDateTime($data);

            $data['status'] =
                $data['status']
                ?? TravelRequest::STATUS_PENDING;

            $travelRequest = TravelRequest::create($data);

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

            $data['updated_by'] = Auth::id();

            if (array_key_exists('company_name', $data)) {
                $data['company_name'] = $this->cleanText(
                    $data['company_name']
                );
            }

            if (array_key_exists('requested_by', $data)) {
                $data['requested_by'] = $this->cleanText(
                    $data['requested_by']
                );
            }

            if (
                isset($data['request_no']) &&
                !empty($data['request_no'])
            ) {

                $data['request_no'] = strtoupper(
                    trim($data['request_no'])
                );
            }

            $this->prepareTravelDateTime(
                $data,
                $travelRequest
            );

            $travelRequest->update($data);

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

            $travelRequest->deleted_by = Auth::id();

            $travelRequest->save();

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

            if (strlen($time) === 5) {
                $time .= ':00';
            }

            $data['travel_date_time'] =
                $date . ' ' . $time;

            return;
        }

        if (!empty($data['travel_date_time'])) {
            return;
        }

        if (
            $travelRequest &&
            $travelRequest->travel_date_time
        ) {

            $data['travel_date_time'] =
                $travelRequest->travel_date_time
                    ->format('Y-m-d H:i:s');

            return;
        }

        if (!empty($data['travel_from_date'])) {

            $data['travel_date_time'] =
                trim(
                    (string) $data['travel_from_date']
                ) . ' 00:00:00';

            return;
        }

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
