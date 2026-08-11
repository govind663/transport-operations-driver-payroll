<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WorkingSheet\StoreWorkingSheetRequest;
use App\Http\Requests\Backend\WorkingSheet\UpdateWorkingSheetRequest;
use App\Models\DutySlip;
use App\Models\WorkingSheet;
use App\Services\WorkingSheet\WorkingSheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkingSheetController extends Controller
{
    /**
     * Working Sheet Service
     */
    protected WorkingSheetService $workingSheetService;

    /**
     * Constructor
     */
    public function __construct(
        WorkingSheetService $workingSheetService
    ) {
        $this->workingSheetService = $workingSheetService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of working sheets.
     */
    public function index(): View
    {
        $workingSheets =
            $this->workingSheetService->getWorkingSheets();

        return view('backend.working-sheets.index',
            compact('workingSheets')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new working sheet.
     */
    public function create(): View
    {
        $dutySlips = DutySlip::query()
            ->with([
                'dutyAssignment',
                'dutyAssignment.travelRequest',
                'dutyAssignment.driver',
                'dutyAssignment.vehicle',
            ])
            ->whereIn('status', [
                DutySlip::STATUS_OPEN,
                DutySlip::STATUS_STARTED,
                DutySlip::STATUS_COMPLETED,
            ])
            ->latest('id')
            ->get();

        return view('backend.working-sheets.create',
            compact('dutySlips')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created working sheet.
     */
    public function store(
        StoreWorkingSheetRequest $request
    ): RedirectResponse {

        $this->workingSheetService->store(
            $request->validated()
        );

        return redirect()
            ->route('working-sheets.index')
            ->with(
                'success',
                'Working sheet created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified working sheet.
     */
    public function show(
        WorkingSheet $workingSheet
    ): View {

        $workingSheet =
            $this->workingSheetService->findById(
                $workingSheet->id
            );

        return view('backend.working-sheets.show',
            compact('workingSheet')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified working sheet.
     */
    public function edit(
        WorkingSheet $workingSheet
    ): View {

        $workingSheet =
            $this->workingSheetService->findById(
                $workingSheet->id
            );

        $dutySlips = DutySlip::query()
            ->with([
                'dutyAssignment',
                'dutyAssignment.travelRequest',
                'dutyAssignment.driver',
                'dutyAssignment.vehicle',
            ])
            ->latest('id')
            ->get();

        return view('backend.working-sheets.edit',
            compact(
                'workingSheet',
                'dutySlips'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified working sheet.
     */
    public function update(
        UpdateWorkingSheetRequest $request,
        WorkingSheet $workingSheet
    ): RedirectResponse {

        $this->workingSheetService->update(
            $workingSheet,
            $request->validated()
        );

        return redirect()
            ->route('working-sheets.index')
            ->with(
                'success',
                'Working sheet updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified working sheet.
     */
    public function destroy(
        WorkingSheet $workingSheet
    ): RedirectResponse {

        $this->workingSheetService->delete(
            $workingSheet
        );

        return redirect()
            ->route('working-sheets.index')
            ->with(
                'success',
                'Working sheet deleted successfully.'
            );
    }
}