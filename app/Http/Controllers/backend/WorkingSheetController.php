<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WorkingSheet\StoreWorkingSheetRequest;
use App\Http\Requests\Backend\WorkingSheet\UpdateWorkingSheetRequest;
use App\Models\Driver;
use App\Models\DriverAllowance;
use App\Models\DriverExpense;
use App\Models\DutySlip;
use App\Models\WorkingSheet;
use App\Services\WorkingSheet\WorkingSheetService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
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
        $workingSheets = $this->workingSheetService->getWorkingSheets();

        return view('backend.working-sheets.index', compact('workingSheets'));
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
        $drivers = Driver::query()
            ->latest('id')
            ->get();

        $dutySlips = $this->dutySlipQuery()
            ->whereIn('status', [
                DutySlip::STATUS_OPEN,
                DutySlip::STATUS_STARTED,
                DutySlip::STATUS_COMPLETED,
            ])
            ->latest('id')
            ->get();

        // Attach all allowance/expense rows for display, while the amount totals
        // include only approved records for this exact Duty Slip and driver.
        $this->attachAllowanceExpenseRecords($dutySlips);

        // Preview the next server-generated Working Sheet number for the form.
        // The service generates it again inside the store transaction before saving.
        $nextSheetNo = $this->workingSheetService->generateNextSheetNumber();

        return view('backend.working-sheets.create', compact(
            'drivers',
            'dutySlips',
            'nextSheetNo'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created working sheet.
     */
    public function store(StoreWorkingSheetRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $dutySlip = $this->findDutySlipForWorkingSheet(
            (int) $data['duty_slip_id']
        );

        // Work Date and Duty Slip must refer to the same duty day.
        $this->assertDutySlipDateMatchesWorkDate(
            $dutySlip,
            $data['work_date'] ?? null
        );

        // Derive driver_id on the server so it cannot be trusted only from
        // the form's hidden input. Duty Slip assignment is the source of truth.
        $data['driver_id'] = $this->resolveDriverId($dutySlip);

        $this->workingSheetService->store($data);

        return redirect()
            ->route('working-sheets.index')
            ->with('success', 'Working sheet created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified working sheet.
     */
    public function show(WorkingSheet $workingSheet): View
    {
        $workingSheet = $this->workingSheetService->findById($workingSheet->id);

        return view('backend.working-sheets.show', compact('workingSheet'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified working sheet.
     */
    public function edit(WorkingSheet $workingSheet): View
    {
        $workingSheet = $this->workingSheetService->findById($workingSheet->id);

        $drivers = Driver::query()
            ->latest('id')
            ->get();

        $dutySlips = $this->dutySlipQuery()
            ->where(function (Builder $query) use ($workingSheet): void {
                $query->whereIn('status', [
                    DutySlip::STATUS_OPEN,
                    DutySlip::STATUS_STARTED,
                    DutySlip::STATUS_COMPLETED,
                ])
                // Preserve the currently selected Duty Slip even if its status
                // changed after this Working Sheet was created.
                ->orWhere('id', $workingSheet->duty_slip_id);
            })
            ->latest('id')
            ->get();

        // Load every allowance/expense detail row for display in the Edit form.
        // The form totals include only approved rows for the selected driver + Duty Slip.
        $this->attachAllowanceExpenseRecords($dutySlips);

        return view('backend.working-sheets.edit', compact(
            'workingSheet',
            'drivers',
            'dutySlips'
        ));
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
        $data = $request->validated();

        $dutySlipId = (int) ($data['duty_slip_id'] ?? $workingSheet->duty_slip_id);
        $dutySlip = $this->findDutySlipForWorkingSheet($dutySlipId);

        // Enforce date matching when either the selected Duty Slip or Work Date
        // changes. This still permits edits to legacy records whose existing
        // Duty Slip/Work Date pair was already mismatched, provided it is unchanged.
        $submittedWorkDate = isset($data['work_date'])
            ? substr((string) $data['work_date'], 0, 10)
            : null;
        $existingWorkDate = $workingSheet->work_date instanceof \DateTimeInterface
            ? $workingSheet->work_date->format('Y-m-d')
            : substr((string) $workingSheet->getAttribute('work_date'), 0, 10);
        $dutySlipChanged = $dutySlipId !== (int) $workingSheet->duty_slip_id;
        $workDateChanged = $submittedWorkDate !== null
            && $submittedWorkDate !== $existingWorkDate;

        if ($dutySlipChanged || $workDateChanged) {
            $this->assertDutySlipDateMatchesWorkDate(
                $dutySlip,
                $submittedWorkDate
            );
        }

        // Keep Working Sheet driver aligned with its selected Duty Slip.
        $data['driver_id'] = $this->resolveDriverId($dutySlip);

        $this->workingSheetService->update($workingSheet, $data);

        return redirect()
            ->route('working-sheets.index')
            ->with('success', 'Working sheet updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified working sheet.
     */
    public function destroy(WorkingSheet $workingSheet): RedirectResponse
    {
        $this->workingSheetService->delete($workingSheet);

        return redirect()
            ->route('working-sheets.index')
            ->with('success', 'Working sheet deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DUTY SLIP QUERY / DRIVER RESOLUTION
    |--------------------------------------------------------------------------
    */

    /**
     * Build the Duty Slip query with the relationships used by the form.
     * A direct DutySlip::driver relationship is eager-loaded only when the
     * relationship exists in the DutySlip model; assignment-based driver data
     * remains supported as a fallback.
     */
    private function dutySlipQuery(): Builder
    {
        $query = DutySlip::query()
            ->with([
                'dutyAssignment',
                'dutyAssignment.travelRequest',
                'dutyAssignment.driver',
                'dutyAssignment.vehicle',
            ])
            // The DutySlip relationships already scope these sums to approved rows.
            // Loading aggregate values here avoids one database query per option.
            ->withSum(
                'approvedDriverAllowances as working_sheet_allowance_total',
                'amount'
            )
            ->withSum(
                'approvedDriverExpenses as working_sheet_expense_total',
                'amount'
            );

        if (method_exists(DutySlip::class, 'driver')) {
            $query->with('driver');
        }

        return $query;
    }

    /**
     * Attach allowance and expense row data to each Duty Slip for the Create form.
     * All statuses are shown in the detail tables; only approved rows contribute
     * to the Allowance Amount and Expense Amount totals.
     */
    private function attachAllowanceExpenseRecords($dutySlips): void
    {
        $dutySlipIds = $dutySlips->pluck('id')->map(static function ($id): int {
            return (int) $id;
        })->all();

        if ($dutySlipIds === []) {
            return;
        }

        $allowancesBySlip = DriverAllowance::query()
            ->with('allowance')
            ->whereIn('duty_slip_id', $dutySlipIds)
            ->get()
            ->groupBy(static function ($record): string {
                return (string) $record->duty_slip_id;
            });

        $expensesBySlip = DriverExpense::query()
            ->with('expense')
            ->whereIn('duty_slip_id', $dutySlipIds)
            ->get()
            ->groupBy(static function ($record): string {
                return (string) $record->duty_slip_id;
            });

        foreach ($dutySlips as $dutySlip) {
            $driverId = $dutySlip->getAttribute('driver_id');

            if (!$driverId && method_exists($dutySlip, 'driver')) {
                $driverId = $dutySlip->driver?->getKey();
            }

            if (!$driverId) {
                $assignment = $dutySlip->dutyAssignment;
                $driverId = $assignment?->getAttribute('driver_id')
                    ?? $assignment?->driver?->getKey();
            }

            $allowanceRecords = collect(
                $allowancesBySlip->get((string) $dutySlip->id, collect())
            )->filter(static function ($record) use ($driverId): bool {
                return $driverId && (int) $record->driver_id === (int) $driverId;
            })->map(static function ($record): array {
                $definition = $record->allowance;
                $label = $definition?->name
                    ?? $definition?->title
                    ?? $definition?->allowance_name
                    ?? ('Allowance #' . ($record->allowance_id ?? $record->id));

                return [
                    'id' => (int) $record->id,
                    'label' => (string) $label,
                    'quantity' => (float) ($record->quantity ?? 0),
                    'rate' => (float) ($record->rate ?? 0),
                    'amount' => (float) ($record->amount ?? 0),
                    'status' => (string) ($record->status ?? 'pending'),
                    'remarks' => (string) ($record->remarks ?? ''),
                ];
            })->values();

            $expenseRecords = collect(
                $expensesBySlip->get((string) $dutySlip->id, collect())
            )->filter(static function ($record) use ($driverId): bool {
                return $driverId && (int) $record->driver_id === (int) $driverId;
            })->map(static function ($record): array {
                $definition = $record->expense;
                $label = $definition?->name
                    ?? $definition?->title
                    ?? $definition?->expense_name
                    ?? ('Expense #' . ($record->expense_id ?? $record->id));

                return [
                    'id' => (int) $record->id,
                    'label' => (string) $label,
                    'quantity' => (float) ($record->quantity ?? 0),
                    'rate' => (float) ($record->rate ?? 0),
                    'amount' => (float) ($record->amount ?? 0),
                    'status' => (string) ($record->status ?? 'pending'),
                    'remarks' => (string) ($record->remarks ?? ''),
                    'document_file' => (string) ($record->document_file ?? ''),
                ];
            })->values();

            $approvedAllowanceTotal = $allowanceRecords
                ->filter(static function (array $record): bool {
                    return strtolower($record['status']) === 'approved';
                })
                ->sum('amount');

            $approvedExpenseTotal = $expenseRecords
                ->filter(static function (array $record): bool {
                    return strtolower($record['status']) === 'approved';
                })
                ->sum('amount');

            $dutySlip->setAttribute(
                'working_sheet_allowance_records',
                $allowanceRecords->all()
            );
            $dutySlip->setAttribute(
                'working_sheet_expense_records',
                $expenseRecords->all()
            );
            $dutySlip->setAttribute(
                'working_sheet_allowance_total',
                round((float) $approvedAllowanceTotal, 2)
            );
            $dutySlip->setAttribute(
                'working_sheet_expense_total',
                round((float) $approvedExpenseTotal, 2)
            );
        }
    }

    /**
     * Find a Duty Slip and load the relationships needed to resolve its driver.
     */
    private function findDutySlipForWorkingSheet(int $dutySlipId): DutySlip
    {
        $query = $this->dutySlipQuery();

        $dutySlip = $query->find($dutySlipId);

        if (!$dutySlip) {
            throw ValidationException::withMessages([
                'duty_slip_id' => 'The selected Duty Slip could not be found.',
            ]);
        }

        return $dutySlip;
    }

    /**
     * Ensure the selected Duty Slip belongs to the requested Working Sheet date.
     */
    private function assertDutySlipDateMatchesWorkDate(
        DutySlip $dutySlip,
        ?string $workDate
    ): void {
        if (!$workDate) {
            return;
        }

        $dutyDate = $dutySlip->getAttribute('duty_date');

        if ($dutyDate instanceof \DateTimeInterface) {
            $dutyDate = $dutyDate->format('Y-m-d');
        } else {
            $dutyDate = $dutyDate ? substr((string) $dutyDate, 0, 10) : '';
        }

        $workDate = substr($workDate, 0, 10);

        if ($dutyDate !== '' && $dutyDate !== $workDate) {
            throw ValidationException::withMessages([
                'work_date' => 'The selected Duty Slip does not belong to the selected Work Date. Please select the matching Duty Slip.',
            ]);
        }
    }

    /**
     * Resolve the assigned driver from the Duty Slip first, then its assignment.
     */
    private function resolveDriverId(DutySlip $dutySlip): int
    {
        $driverId = $dutySlip->getAttribute('driver_id');

        if (!$driverId && method_exists($dutySlip, 'driver')) {
            $driverId = $dutySlip->driver?->getKey();
        }

        if (!$driverId) {
            $assignment = $dutySlip->dutyAssignment;
            $driverId = $assignment?->getAttribute('driver_id')
                ?? $assignment?->driver?->getKey();
        }

        if (!$driverId) {
            throw ValidationException::withMessages([
                'driver_id' => 'The selected Duty Slip does not have an assigned driver.',
            ]);
        }

        return (int) $driverId;
    }
}
