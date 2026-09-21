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
        | Active Duty Assignments
        |--------------------------------------------------------------------------
        |
        | Duty Assignment is mandatory on the Duty Slip.
        | Driver / Vehicle / Vehicle Type remain independent selections.
        |
        */

        $dutyAssignments = DutyAssignment::query()
            ->whereIn('status', [
                DutyAssignment::STATUS_ASSIGNED,
                DutyAssignment::STATUS_ACCEPTED,
                DutyAssignment::STATUS_STARTED,
            ])
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
            ])
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Allowances
        |--------------------------------------------------------------------------
        */

        $allowances = Allowance::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        $drivers = Driver::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Vehicles
        |--------------------------------------------------------------------------
        */

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Vehicle Types
        |--------------------------------------------------------------------------
        */

        $vehicleTypes = VehicleType::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Next Duty Slip Number
        |--------------------------------------------------------------------------
        */

        $nextSlipNo = $this->dutySlipService
            ->generateNextSlipNo();

        /*
        |--------------------------------------------------------------------------
        | View
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
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Prepare Data
        |--------------------------------------------------------------------------
        */

        $data = $this->prepareDutySlipData($data);

        /*
        |--------------------------------------------------------------------------
        | Store
        |--------------------------------------------------------------------------
        */

        $this->dutySlipService->store($data);

        /*
        |--------------------------------------------------------------------------
        | Redirect
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
            ->findById($dutySlip->id);

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
        | Fresh Duty Slip
        |--------------------------------------------------------------------------
        */

        $dutySlip = $this->dutySlipService
            ->findById($dutySlip->id);

        /*
        |--------------------------------------------------------------------------
        | All Duty Assignments
        |--------------------------------------------------------------------------
        */

        $dutyAssignments = DutyAssignment::query()
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
            ])
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Allowances
        |--------------------------------------------------------------------------
        */

        $allowances = Allowance::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        $drivers = Driver::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Vehicles
        |--------------------------------------------------------------------------
        */

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Vehicle Types
        |--------------------------------------------------------------------------
        */

        $vehicleTypes = VehicleType::query()
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | View
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
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Prepare Data
        |--------------------------------------------------------------------------
        */

        $data = $this->prepareDutySlipData(
            $data,
            $dutySlip
        );

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $this->dutySlipService->update(
            $dutySlip,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
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
     * - Duty Assignment is only a required reference.
     * - Duty Assignment never overwrites Driver / Vehicle / Vehicle Type.
     * - Date/time normalization is handled by DutySlipService.
     */
    protected function prepareDutySlipData(
        array $data,
        ?DutySlip $dutySlip = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | DUTY ASSIGNMENT
        |--------------------------------------------------------------------------
        */

        $dutyAssignmentId = $data['duty_assignment_id'] ?? null;

        if (
            $dutyAssignmentId === null ||
            $dutyAssignmentId === ''
        ) {
            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Please select a duty assignment.',
            ]);
        }

        $dutyAssignment = DutyAssignment::query()
            ->select([
                'id',
                'driver_id',
                'vehicle_id',
            ])
            ->find($dutyAssignmentId);

        if (!$dutyAssignment) {
            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Selected duty assignment does not exist.',
            ]);
        }

        $data['duty_assignment_id'] = (int) $dutyAssignment->id;

        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        |
        | Manual selection.
        | Never replaced by duty assignment driver.
        |
        */

        $driverId = $data['driver_id'] ?? null;

        if (
            $driverId === null ||
            $driverId === ''
        ) {
            throw ValidationException::withMessages([
                'driver_id' =>
                    'Please select a driver.',
            ]);
        }

        $driverId = (int) $driverId;

        if ($driverId <= 0) {
            throw ValidationException::withMessages([
                'driver_id' =>
                    'Please select a valid driver.',
            ]);
        }

        $driverExists = Driver::query()
            ->where('id', $driverId)
            ->exists();

        if (!$driverExists) {
            throw ValidationException::withMessages([
                'driver_id' =>
                    'Selected driver does not exist.',
            ]);
        }

        $data['driver_id'] = $driverId;

        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        |
        | Manual selection.
        | This field MUST remain in $data because duty_slips.vehicle_id
        | exists in the current database schema.
        |
        */

        $vehicleId = $data['vehicle_id'] ?? null;

        if (
            $vehicleId === null ||
            $vehicleId === ''
        ) {
            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Please select a vehicle.',
            ]);
        }

        $vehicleId = (int) $vehicleId;

        if ($vehicleId <= 0) {
            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Please select a valid vehicle.',
            ]);
        }

        $vehicleExists = VehicleManagement::query()
            ->where('id', $vehicleId)
            ->exists();

        if (!$vehicleExists) {
            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Selected vehicle does not exist.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Do NOT unset vehicle_id.
        |
        */

        $data['vehicle_id'] = $vehicleId;

        /*
        |--------------------------------------------------------------------------
        | VEHICLE TYPE
        |--------------------------------------------------------------------------
        |
        | Manual selection.
        | Current field is vehicle_type_id.
        |
        */

        $vehicleTypeId = $data['vehicle_type_id'] ?? null;

        if (
            $vehicleTypeId === null ||
            $vehicleTypeId === ''
        ) {
            throw ValidationException::withMessages([
                'vehicle_type_id' =>
                    'Please select a vehicle type.',
            ]);
        }

        $vehicleTypeId = (int) $vehicleTypeId;

        if ($vehicleTypeId <= 0) {
            throw ValidationException::withMessages([
                'vehicle_type_id' =>
                    'Please select a valid vehicle type.',
            ]);
        }

        $vehicleTypeExists = VehicleType::query()
            ->where('id', $vehicleTypeId)
            ->exists();

        if (!$vehicleTypeExists) {
            throw ValidationException::withMessages([
                'vehicle_type_id' =>
                    'Selected vehicle type does not exist.',
            ]);
        }

        $data['vehicle_type_id'] = $vehicleTypeId;

        /*
        |--------------------------------------------------------------------------
        | LEGACY VEHICLE TYPE
        |--------------------------------------------------------------------------
        |
        | Keep only for backward compatibility.
        | The actual database field is vehicle_type_id.
        |
        */

        if (isset($data['vehicle_type'])) {
            $legacyVehicleType = trim(
                (string) $data['vehicle_type']
            );

            $data['vehicle_type'] = $legacyVehicleType !== ''
                ? $legacyVehicleType
                : null;
        }

        /*
        |--------------------------------------------------------------------------
        | DUTY DATE
        |--------------------------------------------------------------------------
        |
        | Form Request already validates this.
        | Keep the value untouched for Service normalization.
        |
        */

        if (empty($data['duty_date'])) {
            throw ValidationException::withMessages([
                'duty_date' =>
                    'Duty date is required.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | KILOMETERS
        |--------------------------------------------------------------------------
        |
        | Do not calculate database totals here.
        | DutySlipService is authoritative for opening/closing/total KM.
        |
        */

        if (
            isset($data['opening_km']) &&
            $data['opening_km'] !== ''
        ) {
            $data['opening_km'] = (float) $data['opening_km'];
        }

        if (
            isset($data['closing_km']) &&
            $data['closing_km'] !== ''
        ) {
            $data['closing_km'] = (float) $data['closing_km'];
        }

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
        | Do NOT combine start_date/start_time here.
        |
        | DutySlipService handles:
        | - start_date + start_time
        | - end_date + end_time
        | - already-combined datetime values
        |
        */

        /*
        |--------------------------------------------------------------------------
        | CHILD ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances = $data['driver_allowances'] ?? [];

        $data['allowances'] = is_array($allowances)
            ? $allowances
            : [];

        /*
        |--------------------------------------------------------------------------
        | CHILD EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = $data['driver_expenses'] ?? [];

        $data['expenses'] = is_array($expenses)
            ? $expenses
            : [];

        /*
        |--------------------------------------------------------------------------
        | FORM-ONLY / DISPLAY-ONLY FIELDS
        |--------------------------------------------------------------------------
        |
        | Do NOT remove:
        | - driver_id
        | - vehicle_id
        | - vehicle_type_id
        |
        | These are real duty_slips database columns.
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
