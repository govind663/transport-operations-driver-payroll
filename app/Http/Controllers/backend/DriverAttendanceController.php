<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DriverAttendance\StoreDriverAttendanceRequest;
use App\Http\Requests\Backend\DriverAttendance\UpdateDriverAttendanceRequest;
use App\Models\Driver;
use App\Models\User;
use App\Models\DriverAttendance;
use App\Services\DriverAttendance\DriverAttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DriverAttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Driver Attendance Service
    |--------------------------------------------------------------------------
    */

    protected DriverAttendanceService $driverAttendanceService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        DriverAttendanceService $driverAttendanceService
    ) {
        $this->driverAttendanceService =
            $driverAttendanceService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $this->authorizeAttendanceRole($user);

        // Validate optional filters so invalid dates/statuses cannot reach the query.
        $request->validate([
            'driver_id' => ['nullable', 'integer', 'exists:drivers,id'],
            'attendance_date' => ['nullable', 'date'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:' . implode(',', DriverAttendance::STATUSES)],
            'source' => ['nullable', 'string', 'in:' . implode(',', DriverAttendance::SOURCES)],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $isAdmin = $user->isAdmin();

        $isOperations = $user->isOperations();

        $isAccountant = $user->isAccountant();

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = DriverAttendance::query()
            ->with([
                'driver',
                'workingSheet',
                'createdBy',
                'updatedBy',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Driver can only see attendance belonging to
        | the Driver record mapped with the logged-in User.
        |
        */

        if ($isDriver) {

            $driver = $user->driver;

            if ($driver) {

                $query->where(
                    'driver_id',
                    $driver->id
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Driver Not Mapped
                |--------------------------------------------------------------------------
                */

                $query->whereRaw('1 = 0');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Filter
        |--------------------------------------------------------------------------
        |
        | Admin / Operations / Accountant can filter
        | attendance by Driver.
        |
        */

        if (
            !$isDriver &&
            $request->filled('driver_id')
        ) {

            $query->where(
                'driver_id',
                $request->driver_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Exact Attendance Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('attendance_date')) {

            $query->whereDate(
                'attendance_date',
                $request->attendance_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'attendance_date',
                '>=',
                $request->date_from
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'attendance_date',
                '<=',
                $request->date_to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Source Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('source')) {

            $query->where(
                'source',
                $request->source
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->whereHas(
                'driver',
                function ($driverQuery) use ($search) {

                    $driverQuery->where(function ($searchQuery) use ($search) {
                        $searchQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw(
                                "CONCAT_WS(' ', first_name, last_name) LIKE ?",
                                ["%{$search}%"]
                            )
                            ->orWhere('driver_code', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        |
        | Driver dropdown is not required for Driver users.
        |
        */

        $drivers = collect();

        if (!$isDriver) {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            DriverAttendance::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | Sources
        |--------------------------------------------------------------------------
        */

        $sources =
            DriverAttendance::SOURCES;


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('backend.driver-attendances.index',
            compact(
                'attendances',
                'drivers',
                'statuses',
                'sources',
                'isAdmin',
                'isOperations',
                'isAccountant',
                'isDriver'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(): View
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $this->authorizeAttendanceRole($user);

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            /*
            |--------------------------------------------------------------------------
            | Driver can only create attendance for himself.
            |--------------------------------------------------------------------------
            */

            $driver = $user->driver;

            if (!$driver) {

                abort(
                    403,
                    'Your user account is not mapped with a driver.'
                );
            }

            $drivers = collect([
                $driver,
            ]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Admin / Operations / Accountant
            |--------------------------------------------------------------------------
            */

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            DriverAttendance::STATUSES;


        return view('backend.driver-attendances.create',
            compact(
                'drivers',
                'statuses',
                'isDriver'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(
        StoreDriverAttendanceRequest $request
    ): RedirectResponse {

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $this->authorizeAttendanceRole($user);

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Driver users cannot create attendance for another Driver.
        |
        */

        if ($user->isDriver()) {

            $driver = $user->driver;

            if (!$driver) {

                abort(
                    403,
                    'Your user account is not mapped with a driver.'
                );
            }

            $data['driver_id'] =
                $driver->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Attendance
        |--------------------------------------------------------------------------
        */

        $this->driverAttendanceService
            ->markManualAttendance(
                $data
            );


        return redirect()
            ->route('driver-attendances.index')
            ->with(
                'message',
                'Driver attendance created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(
        DriverAttendance $driverAttendance
    ): View {

        $this->authorizeDriverAttendance(
            $driverAttendance
        );


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $driverAttendance->load([
            'driver',
            'workingSheet',
            'createdBy',
            'updatedBy',
        ]);


        return view('backend.driver-attendances.show',
            compact(
                'driverAttendance'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(
        DriverAttendance $driverAttendance
    ): View {

        $this->authorizeDriverAttendance(
            $driverAttendance
        );


        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Load Attendance
        |--------------------------------------------------------------------------
        */

        $driverAttendance->load([
            'driver',
            'workingSheet',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $driver = $user->driver;

            if (!$driver) {

                abort(
                    403,
                    'Your user account is not mapped with a driver.'
                );
            }

            $drivers = collect([
                $driver,
            ]);

        } else {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            DriverAttendance::STATUSES;


        return view('backend.driver-attendances.edit',
            compact(
                'driverAttendance',
                'drivers',
                'statuses',
                'isDriver'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(
        UpdateDriverAttendanceRequest $request,
        DriverAttendance $driverAttendance
    ): RedirectResponse {

        $this->authorizeDriverAttendance(
            $driverAttendance
        );


        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Driver can only update his own attendance.
        |
        */

        if ($user->isDriver()) {

            $driver = $user->driver;

            if (!$driver) {

                abort(
                    403,
                    'Your user account is not mapped with a driver.'
                );
            }

            $data['driver_id'] =
                $driver->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Manual Update
        |--------------------------------------------------------------------------
        */

        $data['source'] = DriverAttendance::SOURCE_MANUAL;

        // Once manually edited, detach the row from its source Working Sheet.
        // Otherwise a later automatic sync could overwrite this manual update.
        $data['working_sheet_id'] = null;
        $data['updated_by'] = Auth::id();
        $data['driver_id'] = (int) ($data['driver_id'] ?? $driverAttendance->driver_id);
        $data['attendance_date'] = $data['attendance_date']
            ?? $driverAttendance->getRawOriginal('attendance_date');

        /*
        |--------------------------------------------------------------------------
        | Preserve Created By
        |--------------------------------------------------------------------------
        */

        if (!$driverAttendance->created_by) {
            $data['created_by'] = Auth::id();
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Attendance
        |--------------------------------------------------------------------------
        |
        | The database unique key also applies to soft-deleted rows, so include
        | trashed records in the conflict check before attempting the update.
        |
        */

        try {
            DB::transaction(function () use (
                $driverAttendance,
                $data
            ) {
                $duplicateExists = DriverAttendance::withTrashed()
                    ->where('driver_id', $data['driver_id'])
                    ->whereDate('attendance_date', $data['attendance_date'])
                    ->where('id', '!=', $driverAttendance->getKey())
                    ->lockForUpdate()
                    ->exists();

                if ($duplicateExists) {
                    throw ValidationException::withMessages([
                        'attendance_date' => 'An attendance record already exists for this driver on this date.',
                    ]);
                }

                $driverAttendance->fill($data);
                $driverAttendance->save();
            });
        } catch (QueryException $exception) {
            // Convert a concurrent unique-key conflict into a normal form error.
            if (
                (string) $exception->getCode() === '23000' &&
                str_contains($exception->getMessage(), 'driver_attendance_unique')
            ) {
                throw ValidationException::withMessages([
                    'attendance_date' => 'An attendance record already exists for this driver on this date.',
                ]);
            }

            throw $exception;
        }


        return redirect()
            ->route('driver-attendances.index')
            ->with(
                'message',
                'Driver attendance updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(
        DriverAttendance $driverAttendance
    ): RedirectResponse {

        $this->authorizeDriverAttendance(
            $driverAttendance
        );


        /*
        |--------------------------------------------------------------------------
        | Soft Delete
        |--------------------------------------------------------------------------
        */

        $driverAttendance->delete();


        return redirect()
            ->route('driver-attendances.index')
            ->with(
                'message',
                'Driver attendance deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE DRIVER ATTENDANCE
    |--------------------------------------------------------------------------
    |
    | Admin / Operations / Accountant:
    | Can access all attendance records.
    |
    | Driver:
    | Can access only his own mapped attendance.
    |
    */
    protected function authorizeAttendanceRole(User $user): void
    {
        if (
            !$user->isAdmin() &&
            !$user->isOperations() &&
            !$user->isAccountant() &&
            !$user->isDriver()
        ) {
            abort(403, 'You are not authorized to manage driver attendance.');
        }
    }


    protected function authorizeDriverAttendance(
        DriverAttendance $driverAttendance
    ): void {

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Non Driver
        |--------------------------------------------------------------------------
        |
        | Admin / Operations / Accountant can access
        | all driver attendance records.
        |
        */

        $this->authorizeAttendanceRole($user);

        if (!$user->isDriver()) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Mapping
        |--------------------------------------------------------------------------
        */

        $driver = $user->driver;


        if (!$driver) {

            abort(
                403,
                'Your user account is not mapped with a driver.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if (
            (int) $driverAttendance->driver_id !==
            (int) $driver->id
        ) {

            abort(
                403,
                'You are not authorized to access this attendance record.'
            );
        }
    }
}
