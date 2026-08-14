<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Allowance\StoreAllowanceRequest;
use App\Http\Requests\Backend\Allowance\UpdateAllowanceRequest;
use App\Models\Allowance;
use App\Services\Allowance\AllowanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AllowanceController extends Controller
{
    /**
     * Allowance Service
     */
    protected AllowanceService $allowanceService;

    /**
     * Constructor
     */
    public function __construct(
        AllowanceService $allowanceService
    ) {
        $this->allowanceService = $allowanceService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of allowances.
     */
    public function index(): View
    {
        $allowances =
            $this->allowanceService->getAllowances();

        return view(
            'backend.allowances.index',
            compact('allowances')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new allowance.
     */
    public function create(): View
    {
        return view(
            'backend.allowances.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created allowance.
     */
    public function store(
        StoreAllowanceRequest $request
    ): RedirectResponse {

        $this->allowanceService->store(
            $request->validated()
        );

        return redirect()
            ->route('allowances.index')
            ->with(
                'success',
                'Allowance created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified allowance.
     */
    public function show(
        Allowance $allowance
    ): View {

        $allowance =
            $this->allowanceService->findById(
                $allowance->id
            );

        return view(
            'backend.allowances.show',
            compact('allowance')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified allowance.
     */
    public function edit(
        Allowance $allowance
    ): View {

        $allowance =
            $this->allowanceService->findById(
                $allowance->id
            );

        return view(
            'backend.allowances.edit',
            compact('allowance')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified allowance.
     */
    public function update(
        UpdateAllowanceRequest $request,
        Allowance $allowance
    ): RedirectResponse {

        $this->allowanceService->update(
            $allowance,
            $request->validated()
        );

        return redirect()
            ->route('allowances.index')
            ->with(
                'success',
                'Allowance updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified allowance.
     */
    public function destroy(
        Allowance $allowance
    ): RedirectResponse {

        $this->allowanceService->delete(
            $allowance
        );

        return redirect()
            ->route('allowances.index')
            ->with(
                'success',
                'Allowance deleted successfully.'
            );
    }
}