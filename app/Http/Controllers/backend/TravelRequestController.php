<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TravelRequest\StoreTravelRequestRequest;
use App\Http\Requests\Backend\TravelRequest\UpdateTravelRequestRequest;
use App\Models\Client;
use App\Models\TravelRequest;
use App\Services\TravelRequest\TravelRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TravelRequestController extends Controller
{
    /**
     * Travel Request Service
     */
    protected TravelRequestService $travelRequestService;

    /**
     * Constructor
     */
    public function __construct(
        TravelRequestService $travelRequestService
    ) {
        $this->travelRequestService = $travelRequestService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of travel requests.
     */
    public function index(): View
    {
        $travelRequests =
            $this->travelRequestService->getTravelRequests();

        return view('backend.travel-requests.index',
            compact('travelRequests')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new travel request.
     */
    public function create(): View
    {
        $clients = Client::query()
            ->orderBy('client_code')
            ->get();

        return view('backend.travel-requests.create',
            compact('clients')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created travel request.
     */
    public function store(
        StoreTravelRequestRequest $request
    ): RedirectResponse {

        $this->travelRequestService->store(
            $request->validated()
        );

        return redirect()
            ->route('travel-requests.index')
            ->with(
                'success',
                'Travel request created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified travel request.
     */
    public function show(
        TravelRequest $travelRequest
    ): View {

        $travelRequest =
            $this->travelRequestService->findById(
                $travelRequest->id
            );

        return view('backend.travel-requests.show',
            compact('travelRequest')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified travel request.
     */
    public function edit(
        TravelRequest $travelRequest
    ): View {

        $travelRequest =
            $this->travelRequestService->findById(
                $travelRequest->id
            );

        $clients = Client::query()
            ->orderBy('client_code')
            ->get();

        return view('backend.travel-requests.edit',
            compact(
                'travelRequest',
                'clients'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified travel request.
     */
    public function update(
        UpdateTravelRequestRequest $request,
        TravelRequest $travelRequest
    ): RedirectResponse {

        $this->travelRequestService->update(
            $travelRequest,
            $request->validated()
        );

        return redirect()
            ->route('travel-requests.index')
            ->with(
                'success',
                'Travel request updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified travel request.
     */
    public function destroy(
        TravelRequest $travelRequest
    ): RedirectResponse {

        $this->travelRequestService->delete(
            $travelRequest
        );

        return redirect()
            ->route('travel-requests.index')
            ->with(
                'success',
                'Travel request deleted successfully.'
            );
    }
}