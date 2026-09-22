<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DutySlip\StoreDutySlipRequest;
use App\Http\Requests\Backend\DutySlip\UpdateDutySlipRequest;
use App\Models\Allowance;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\DutySlip;
use App\Models\Expense;
use App\Models\VehicleManagement;
use App\Models\VehicleType;
use App\Services\DutySlip\DutySlipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DutySlipController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Duty Slip Service
    |--------------------------------------------------------------------------
    */

    protected DutySlipService $dutySlipService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        DutySlipService $dutySlipService
    ) {
        $this->dutySlipService = $dutySlipService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of duty slips.
     */
    public function index(): View
    {
        $dutySlips = $this->dutySlipService
            ->getDutySlips();

        return view(
            'backend.duty-slips.index',
            compact('dutySlips')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new duty slip.
     */
    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | DUTY ASSIGNMENTS
        |--------------------------------------------------------------------------
        |
        | admin       -> all
        | operations  -> all
        | accountant  -> all
        | driver      -> only own assignments
        |
        */

        $dutyAssignments = $this->getDutyAssignmentsForCurrentUser();


        /*
        |--------------------------------------------------------------------------
        | ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances = Allowance::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL DRIVERS
        |--------------------------------------------------------------------------
        |
        | Driver / Vehicle / Vehicle Type remain independently selectable.
        |
        */

        $drivers = Driver::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLES
        |--------------------------------------------------------------------------
        */

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLE TYPES
        |--------------------------------------------------------------------------
        */

        $vehicleTypes = VehicleType::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NEXT DUTY SLIP NUMBER
        |--------------------------------------------------------------------------
        */

        $nextSlipNo = $this->dutySlipService
            ->generateNextSlipNo();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.duty-slips.create',
            compact(
                'dutyAssignments',
                'allowances',
                'expenses',
                'drivers',
                'vehicles',
                'vehicleTypes',
                'nextSlipNo'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created duty slip.
     */
    public function store(
        StoreDutySlipRequest $request
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDATED DATA
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | PREPARE DATA
        |--------------------------------------------------------------------------
        */

        $data = $this->prepareDutySlipData(
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        $this->dutySlipService->store(
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('duty-slips.index')
            ->with(
                'message',
                'Duty slip created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified duty slip.
     */
    public function show(
        DutySlip $dutySlip
    ): View {
        $dutySlip = $this->dutySlipService
            ->findById(
                $dutySlip->id
            );

        return view(
            'backend.duty-slips.show',
            compact('dutySlip')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified duty slip.
     */
    public function edit(
        DutySlip $dutySlip
    ): View {
        /*
        |--------------------------------------------------------------------------
        | FRESH DUTY SLIP
        |--------------------------------------------------------------------------
        */

        $dutySlip = $this->dutySlipService
            ->findById(
                $dutySlip->id
            );


        /*
        |--------------------------------------------------------------------------
        | DUTY ASSIGNMENTS
        |--------------------------------------------------------------------------
        |
        | admin       -> all
        | operations  -> all
        | accountant  -> all
        | driver      -> only own assignments
        |
        */

        $dutyAssignments = $this->getDutyAssignmentsForCurrentUser();


        /*
        |--------------------------------------------------------------------------
        | ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances = Allowance::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL DRIVERS
        |--------------------------------------------------------------------------
        */

        $drivers = Driver::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLES
        |--------------------------------------------------------------------------
        */

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLE TYPES
        |--------------------------------------------------------------------------
        */

        $vehicleTypes = VehicleType::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.duty-slips.edit',
            compact(
                'dutySlip',
                'dutyAssignments',
                'allowances',
                'expenses',
                'drivers',
                'vehicles',
                'vehicleTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified duty slip.
     */
    public function update(
        UpdateDutySlipRequest $request,
        DutySlip $dutySlip
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDATED DATA
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | PREPARE DATA
        |--------------------------------------------------------------------------
        */

        $data = $this->prepareDutySlipData(
            $data,
            $dutySlip
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $this->dutySlipService->update(
            $dutySlip,
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('duty-slips.index')
            ->with(
                'message',
                'Duty slip updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified duty slip.
     */
    public function destroy(
        DutySlip $dutySlip
    ): RedirectResponse {
        $this->dutySlipService->delete(
            $dutySlip
        );

        return redirect()
            ->route('duty-slips.index')
            ->with(
                'message',
                'Duty slip deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GET DUTY ASSIGNMENTS FOR CURRENT USER
    |--------------------------------------------------------------------------
    */

    /**
     * Get Duty Assignments according to authenticated user's role.
     *
     * Rules:
     * - admin       => all assignments
     * - operations  => all assignments
     * - accountant  => all assignments
     * - driver      => only assignments linked to own driver profile
     */
    protected function getDutyAssignmentsForCurrentUser()
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = DutyAssignment::query()
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
            ])
            ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | DRIVER ROLE
        |--------------------------------------------------------------------------
        |
        | Driver must only see assignments belonging to their own
        | Driver master record.
        |
        | drivers.user_id = authenticated users.id
        |
        */

        if (
            $user &&
            $user->role === 'driver'
        ) {

            $query->whereHas(
                'driver',
                function ($driverQuery) use ($user) {

                    $driverQuery->where(
                        'user_id',
                        $user->id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | OTHER ROLES
        |--------------------------------------------------------------------------
        |
        | admin
        | operations
        | accountant
        |
        | No additional Duty Assignment restriction.
        |
        */

        return $query->get();
    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE DUTY SLIP DATA
    |--------------------------------------------------------------------------
    */

    /**
     * Normalize and validate controller-level Duty Slip data.
     *
     * Important:
     * - Driver is manually selected.
     * - Vehicle is manually selected.
     * - Vehicle Type is manually selected.
     * - Duty Assignment is required.
     * - Duty Assignment does not overwrite Driver.
     * - Duty Assignment does not overwrite Vehicle.
     * - Duty Assignment does not overwrite Vehicle Type.
     * - Date/time normalization is handled by DutySlipService.
     */
    protected function prepareDutySlipData(
        array $data,
        ?DutySlip $dutySlip = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | CURRENT USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | DUTY ASSIGNMENT ID
        |--------------------------------------------------------------------------
        */

        $dutyAssignmentId =
            $data['duty_assignment_id']
            ?? null;


        if (
            $dutyAssignmentId === null ||
            $dutyAssignmentId === ''
        ) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Please select a duty assignment.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DUTY ASSIGNMENT QUERY
        |--------------------------------------------------------------------------
        */

        $dutyAssignmentQuery = DutyAssignment::query()
            ->select([
                'id',
                'driver_id',
                'vehicle_id',
            ])
            ->where(
                'id',
                $dutyAssignmentId
            );


        /*
        |--------------------------------------------------------------------------
        | DRIVER ROLE SECURITY
        |--------------------------------------------------------------------------
        |
        | A driver cannot submit another driver's assignment ID manually.
        |
        */

        if (
            $user &&
            $user->role === 'driver'
        ) {

            $dutyAssignmentQuery->whereHas(
                'driver',
                function ($driverQuery) use ($user) {

                    $driverQuery->where(
                        'user_id',
                        $user->id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FIND DUTY ASSIGNMENT
        |--------------------------------------------------------------------------
        */

        $dutyAssignment =
            $dutyAssignmentQuery->first();


        if (!$dutyAssignment) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    $user && $user->role === 'driver'
                        ? 'You can only select your own duty assignments.'
                        : 'Selected duty assignment does not exist.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE DUTY ASSIGNMENT ID
        |--------------------------------------------------------------------------
        */

        $data['duty_assignment_id'] =
            (int) $dutyAssignment->id;


        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        |
        | Manual selection.
        | Duty Assignment driver is NOT copied here.
        |
        */

        $driverId =
            $data['driver_id']
            ?? null;


        if (
            $driverId === null ||
            $driverId === ''
        ) {

            throw ValidationException::withMessages([
                'driver_id' =>
                    'Please select a driver.',
            ]);
        }


        $driverId =
            (int) $driverId;


        if ($driverId <= 0) {

            throw ValidationException::withMessages([
                'driver_id' =>
                    'Please select a valid driver.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY DRIVER
        |--------------------------------------------------------------------------
        */

        $driverExists = Driver::query()
            ->where(
                'id',
                $driverId
            )
            ->exists();


        if (!$driverExists) {

            throw ValidationException::withMessages([
                'driver_id' =>
                    'Selected driver does not exist.',
            ]);
        }


        $data['driver_id'] =
            $driverId;


        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        |
        | Manual selection.
        | Duty Assignment vehicle is NOT copied here.
        |
        */

        $vehicleId =
            $data['vehicle_id']
            ?? null;


        if (
            $vehicleId === null ||
            $vehicleId === ''
        ) {

            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Please select a vehicle.',
            ]);
        }


        $vehicleId =
            (int) $vehicleId;


        if ($vehicleId <= 0) {

            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Please select a valid vehicle.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY VEHICLE
        |--------------------------------------------------------------------------
        */

        $vehicleExists = VehicleManagement::query()
            ->where(
                'id',
                $vehicleId
            )
            ->exists();


        if (!$vehicleExists) {

            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Selected vehicle does not exist.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | KEEP VEHICLE ID
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Do NOT unset this field.
        |
        */

        $data['vehicle_id'] =
            $vehicleId;


        /*
        |--------------------------------------------------------------------------
        | VEHICLE TYPE
        |--------------------------------------------------------------------------
        |
        | Manual selection.
        |
        */

        $vehicleTypeId =
            $data['vehicle_type_id']
            ?? null;


        if (
            $vehicleTypeId === null ||
            $vehicleTypeId === ''
        ) {

            throw ValidationException::withMessages([
                'vehicle_type_id' =>
                    'Please select a vehicle type.',
            ]);
        }


        $vehicleTypeId =
            (int) $vehicleTypeId;


        if ($vehicleTypeId <= 0) {

            throw ValidationException::withMessages([
                'vehicle_type_id' =>
                    'Please select a valid vehicle type.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY VEHICLE TYPE
        |--------------------------------------------------------------------------
        */

        $vehicleTypeExists = VehicleType::query()
            ->where(
                'id',
                $vehicleTypeId
            )
            ->exists();


        if (!$vehicleTypeExists) {

            throw ValidationException::withMessages([
                'vehicle_type_id' =>
                    'Selected vehicle type does not exist.',
            ]);
        }


        $data['vehicle_type_id'] =
            $vehicleTypeId;


        /*
        |--------------------------------------------------------------------------
        | LEGACY VEHICLE TYPE
        |--------------------------------------------------------------------------
        |
        | Keep only when old forms send it.
        |
        */

        if (isset($data['vehicle_type'])) {

            $legacyVehicleType =
                trim(
                    (string) $data['vehicle_type']
                );


            $data['vehicle_type'] =
                $legacyVehicleType !== ''
                    ? $legacyVehicleType
                    : null;
        }


        /*
        |--------------------------------------------------------------------------
        | DUTY DATE
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $data['duty_date']
            )
        ) {

            throw ValidationException::withMessages([
                'duty_date' =>
                    'Duty date is required.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OPENING KM
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['opening_km']) &&
            $data['opening_km'] !== ''
        ) {

            $data['opening_km'] =
                (float) $data['opening_km'];
        }


        /*
        |--------------------------------------------------------------------------
        | CLOSING KM
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['closing_km']) &&
            $data['closing_km'] !== ''
        ) {

            $data['closing_km'] =
                (float) $data['closing_km'];
        }


        /*
        |--------------------------------------------------------------------------
        | KM VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['opening_km']) &&
            isset($data['closing_km']) &&
            $data['opening_km'] !== null &&
            $data['closing_km'] !== null &&
            $data['closing_km'] < $data['opening_km']
        ) {

            throw ValidationException::withMessages([
                'closing_km' =>
                    'Closing KM must be greater than or equal to Opening KM.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DATE + TIME
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Controller does not combine date + time.
        |
        | DutySlipService handles:
        |
        | start_date + start_time
        | end_date + end_time
        | already combined datetime
        |
        */

        /*
        |--------------------------------------------------------------------------
        | DRIVER ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances =
            $data['driver_allowances']
            ?? [];


        $data['allowances'] =
            is_array($allowances)
                ? $allowances
                : [];


        /*
        |--------------------------------------------------------------------------
        | DRIVER EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses =
            $data['driver_expenses']
            ?? [];


        $data['expenses'] =
            is_array($expenses)
                ? $expenses
                : [];


        /*
        |--------------------------------------------------------------------------
        | REMOVE FORM-ONLY / DISPLAY-ONLY FIELDS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | KEEP:
        | - driver_id
        | - vehicle_id
        | - vehicle_type_id
        | - duty_assignment_id
        |
        */

        unset(
            $data['driver_allowances'],
            $data['driver_expenses'],
            $data['pickup_location'],
            $data['drop_location'],
            $data['passenger_name'],
            $data['passenger_mobile'],
            $data['number_of_passengers'],
            $data['fuel_quantity'],
            $data['fuel_amount'],
            $data['total_allowance'],
            $data['total_expense'],
            $data['grand_total']
        );


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $data;
    }
}