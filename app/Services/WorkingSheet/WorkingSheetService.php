<?php

namespace App\Services\WorkingSheet;

use App\Models\WorkingSheet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkingSheetService
{
    /*
    |--------------------------------------------------------------------------
    | GET WORKING SHEETS
    |--------------------------------------------------------------------------
    */

    public function getWorkingSheets(
        int $perPage = 20
    ): LengthAwarePaginator {

        return WorkingSheet::query()
            ->with([
                'dutySlip',
                'createdBy',
                'updatedBy',
            ])
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND WORKING SHEET
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): WorkingSheet {

        return WorkingSheet::query()
            ->with([
                'dutySlip',
                'createdBy',
                'updatedBy',
            ])
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): WorkingSheet {

        return DB::transaction(function () use ($data) {

            $data['created_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? WorkingSheet::STATUS_DRAFT;

            /*
            |--------------------------------------------------------------------------
            | Calculate Total KM
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['opening_meter']) &&
                isset($data['closing_meter']) &&
                $data['opening_meter'] !== null &&
                $data['closing_meter'] !== null
            ) {

                $data['total_km'] =
                    max(
                        0,
                        (float) $data['closing_meter']
                        - (float) $data['opening_meter']
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Total Amount
            |--------------------------------------------------------------------------
            */

            $baseAmount =
                (float) ($data['base_amount'] ?? 0);

            $extraKmAmount =
                (float) ($data['extra_km_amount'] ?? 0);

            $overtimeAmount =
                (float) ($data['overtime_amount'] ?? 0);

            $otherAmount =
                (float) ($data['other_amount'] ?? 0);

            $data['total_amount'] =
                $baseAmount
                + $extraKmAmount
                + $overtimeAmount
                + $otherAmount;

            return WorkingSheet::create($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        WorkingSheet $workingSheet,
        array $data
    ): WorkingSheet {

        return DB::transaction(function () use (
            $workingSheet,
            $data
        ) {

            $data['updated_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Calculate Total KM
            |--------------------------------------------------------------------------
            */

            $openingMeter =
                $data['opening_meter']
                ?? $workingSheet->opening_meter;

            $closingMeter =
                $data['closing_meter']
                ?? $workingSheet->closing_meter;

            if (
                $openingMeter !== null &&
                $closingMeter !== null
            ) {

                $data['total_km'] =
                    max(
                        0,
                        (float) $closingMeter
                        - (float) $openingMeter
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Total Amount
            |--------------------------------------------------------------------------
            */

            $baseAmount =
                (float) (
                    $data['base_amount']
                    ?? $workingSheet->base_amount
                    ?? 0
                );

            $extraKmAmount =
                (float) (
                    $data['extra_km_amount']
                    ?? $workingSheet->extra_km_amount
                    ?? 0
                );

            $overtimeAmount =
                (float) (
                    $data['overtime_amount']
                    ?? $workingSheet->overtime_amount
                    ?? 0
                );

            $otherAmount =
                (float) (
                    $data['other_amount']
                    ?? $workingSheet->other_amount
                    ?? 0
                );

            $data['total_amount'] =
                $baseAmount
                + $extraKmAmount
                + $overtimeAmount
                + $otherAmount;

            $workingSheet->update($data);

            return $workingSheet->fresh([
                'dutySlip',
                'createdBy',
                'updatedBy',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        WorkingSheet $workingSheet
    ): bool {

        return DB::transaction(function () use (
            $workingSheet
        ) {

            $workingSheet->deleted_by = Auth::id();

            $workingSheet->save();

            return $workingSheet->delete();
        });
    }
}