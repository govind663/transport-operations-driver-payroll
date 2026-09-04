<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DutySlip\StoreDutySlipRequest;
use App\Http\Requests\Backend\DutySlip\UpdateDutySlipRequest;
use App\Models\Allowance;
use App\Models\DutyAssignment;
use App\Models\DutySlip;
use App\Models\Expense;
use App\Services\DutySlip\DutySlipService;
use Illuminate\Http\RedirectResponse;
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

        return view('backend.duty-slips.index',
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

        $allowances = Allowance::query()
            ->latest('id')
            ->get();

        $expenses = Expense::query()
            ->latest('id')
            ->get();

        return view(
            'backend.duty-slips.create',
            compact(
                'dutyAssignments',
                'allowances',
                'expenses'
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
        $this->dutySlipService->store(
            $request->validated()
        );

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

        return view('backend.duty-slips.show',
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
        $dutySlip = $this->dutySlipService
            ->findById(
                $dutySlip->id
            );

        $dutyAssignments = DutyAssignment::query()
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
            ])
            ->latest('id')
            ->get();

        $allowances = Allowance::query()
            ->latest('id')
            ->get();

        $expenses = Expense::query()
            ->latest('id')
            ->get();

        return view('backend.duty-slips.edit',
            compact(
                'dutySlip',
                'dutyAssignments',
                'allowances',
                'expenses'
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
        $this->dutySlipService->update(
            $dutySlip,
            $request->validated()
        );

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
}