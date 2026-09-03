<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TravelRequest\StoreTravelRequestRequest;
use App\Http\Requests\Backend\TravelRequest\UpdateTravelRequestRequest;
use App\Imports\TravelRequestsImport;
use App\Models\TravelRequest;
use App\Services\TravelRequest\TravelRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

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
        $travelRequests = $this->travelRequestService
            ->getTravelRequests();

        return view(
            'backend.travel-requests.index',
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
        return view(
            'backend.travel-requests.create'
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

        $travelRequest = $this->travelRequestService->store(
            $request->validated()
        );

        return redirect()
            ->route('travel-requests.index')
            ->with(
                'success',
                "Travel request {$travelRequest->request_no} created successfully."
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

        $travelRequest = $this->travelRequestService->findById(
            $travelRequest->id
        );

        return view(
            'backend.travel-requests.show',
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

        $travelRequest = $this->travelRequestService->findById(
            $travelRequest->id
        );

        return view(
            'backend.travel-requests.edit',
            compact('travelRequest')
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

        $travelRequest = $this->travelRequestService->update(
            $travelRequest,
            $request->validated()
        );

        return redirect()
            ->route('travel-requests.index')
            ->with(
                'success',
                "Travel request {$travelRequest->request_no} updated successfully."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL IMPORT
    |--------------------------------------------------------------------------
    */

    /**
     * Import travel requests from Excel.
     */
    public function import(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Uploaded File
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            [
                'excel_file' => [
                    'required',
                    'file',
                    'mimes:xlsx,xls,csv',
                    'max:10240',
                ],
            ],
            [
                'excel_file.required' =>
                    'Please select an Excel file.',

                'excel_file.file' =>
                    'The uploaded file is invalid.',

                'excel_file.mimes' =>
                    'Only XLSX, XLS or CSV files are allowed.',

                'excel_file.max' =>
                    'The Excel file must not exceed 10 MB.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Validation Failed
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {

            return redirect()
                ->route('travel-requests.index')
                ->withErrors($validator)
                ->withInput()
                ->with(
                    'import_error',
                    'Please correct the Excel file selection.'
                );
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Import Excel
            |--------------------------------------------------------------------------
            */

            Excel::import(
                new TravelRequestsImport(),
                $request->file('excel_file')
            );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('travel-requests.index')
                ->with(
                    'success',
                    'Travel requests imported successfully.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Import Failed
            |--------------------------------------------------------------------------
            */

            report($e);

            return redirect()
                ->route('travel-requests.index')
                ->with(
                    'import_error',
                    'Excel import failed. Please check the Excel file data and try again.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL TEMPLATE
    |--------------------------------------------------------------------------
    */

    /**
     * Download Excel import template.
     */
    public function importTemplate()
    {
        $headers = [

            'request_no',

            'company_name',

            'requested_by',

            'employee_email',

            'travel_id',

            'trip_id',

            'vendor_name',

            'vehicle_type',

            'travel_from_date',

            'travel_to_date',

            'pickup_time',

            'from_city',

            'pickup_location',

            'drop_location',

            'release_location',

            'passenger_name',

            'passenger_phone',

            'traveler_mobile',

            'employee_id',

            'cost_center',

            'car_hire_type',

            'for_use',

            'gst_number',

            'reporting_address',

            'release_address',

            'release_time',

            'travel_date_time',

            'passenger_count',

            'purpose',

            'specific_instruction',

            'status',

            'remarks',
        ];


        /*
        |--------------------------------------------------------------------------
        | Create CSV Template
        |--------------------------------------------------------------------------
        */

        $callback = function () use ($headers) {

            $file = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | Add UTF-8 BOM
            |--------------------------------------------------------------------------
            */

            fprintf(
                $file,
                chr(0xEF) . chr(0xBB) . chr(0xBF)
            );


            /*
            |--------------------------------------------------------------------------
            | Header Row
            |--------------------------------------------------------------------------
            */

            fputcsv(
                $file,
                $headers
            );


            fclose($file);
        };


        return response()->streamDownload(
            $callback,
            'travel_requests_import_template.csv',
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Soft delete the specified travel request.
     */
    public function destroy(
        TravelRequest $travelRequest
    ): RedirectResponse {

        $requestNo = $travelRequest->request_no;


        $this->travelRequestService->delete(
            $travelRequest
        );


        return redirect()
            ->route('travel-requests.index')
            ->with(
                'success',
                "Travel request {$requestNo} deleted successfully."
            );
    }
}