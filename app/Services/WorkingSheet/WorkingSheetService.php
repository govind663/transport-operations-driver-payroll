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
                'driver',
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
                'driver',
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

            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

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
            | Driver
            |--------------------------------------------------------------------------
            |
            | Driver is stored directly for:
            | - Driver Dashboard
            | - Driver-wise reports
            | - Driver-wise KM
            | - Driver-wise earnings
            |
            */

            if (
                isset($data['driver_id']) &&
                $data['driver_id'] !== ''
            ) {

                $data['driver_id'] =
                    (int) $data['driver_id'];

            } else {

                $data['driver_id'] = null;
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Total KM
            |--------------------------------------------------------------------------
            */

            $openingMeter =
                $data['opening_meter'] ?? null;

            $closingMeter =
                $data['closing_meter'] ?? null;


            if (
                $openingMeter !== null &&
                $closingMeter !== null &&
                $openingMeter !== '' &&
                $closingMeter !== ''
            ) {

                $data['total_km'] =
                    max(
                        0,
                        round(
                            (float) $closingMeter
                            - (float) $openingMeter,
                            2
                        )
                    );

            } else {

                $data['total_km'] = null;
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize Hours
            |--------------------------------------------------------------------------
            */

            $data['total_hours'] =
                isset($data['total_hours']) &&
                $data['total_hours'] !== ''
                    ? round(
                        max(
                            0,
                            (float) $data['total_hours']
                        ),
                        2
                    )
                    : null;


            $data['overtime_hours'] =
                round(
                    max(
                        0,
                        (float) (
                            $data['overtime_hours']
                            ?? 0
                        )
                    ),
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Calculate Financial Amounts
            |--------------------------------------------------------------------------
            */

            $baseAmount =
                max(
                    0,
                    (float) (
                        $data['base_amount']
                        ?? 0
                    )
                );


            $extraKmAmount =
                max(
                    0,
                    (float) (
                        $data['extra_km_amount']
                        ?? 0
                    )
                );


            $overtimeAmount =
                max(
                    0,
                    (float) (
                        $data['overtime_amount']
                        ?? 0
                    )
                );


            $otherAmount =
                max(
                    0,
                    (float) (
                        $data['other_amount']
                        ?? 0
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | Store Normalized Amounts
            |--------------------------------------------------------------------------
            */

            $data['base_amount'] =
                round($baseAmount, 2);

            $data['extra_km_amount'] =
                round($extraKmAmount, 2);

            $data['overtime_amount'] =
                round($overtimeAmount, 2);

            $data['other_amount'] =
                round($otherAmount, 2);


            /*
            |--------------------------------------------------------------------------
            | Total Amount
            |--------------------------------------------------------------------------
            */

            $data['total_amount'] =
                round(
                    $baseAmount
                    + $extraKmAmount
                    + $overtimeAmount
                    + $otherAmount,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Create Working Sheet
            |--------------------------------------------------------------------------
            */

            $workingSheet =
                WorkingSheet::create($data);


            /*
            |--------------------------------------------------------------------------
            | Return With Relationships
            |--------------------------------------------------------------------------
            */

            return $workingSheet->load([
                'dutySlip',
                'driver',
                'createdBy',
                'updatedBy',
            ]);
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

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $data['updated_by'] = Auth::id();


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists(
                    'driver_id',
                    $data
                )
            ) {

                $data['driver_id'] =
                    $data['driver_id'] !== ''
                        ? (int) $data['driver_id']
                        : null;
            }


            /*
            |--------------------------------------------------------------------------
            | Opening Meter
            |--------------------------------------------------------------------------
            */

            $openingMeter =
                array_key_exists(
                    'opening_meter',
                    $data
                )
                    ? $data['opening_meter']
                    : $workingSheet->opening_meter;


            /*
            |--------------------------------------------------------------------------
            | Closing Meter
            |--------------------------------------------------------------------------
            */

            $closingMeter =
                array_key_exists(
                    'closing_meter',
                    $data
                )
                    ? $data['closing_meter']
                    : $workingSheet->closing_meter;


            /*
            |--------------------------------------------------------------------------
            | Calculate Total KM
            |--------------------------------------------------------------------------
            */

            if (
                $openingMeter !== null &&
                $closingMeter !== null &&
                $openingMeter !== '' &&
                $closingMeter !== ''
            ) {

                $data['total_km'] =
                    max(
                        0,
                        round(
                            (float) $closingMeter
                            - (float) $openingMeter,
                            2
                        )
                    );

            } else {

                $data['total_km'] = null;
            }


            /*
            |--------------------------------------------------------------------------
            | Total Hours
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists(
                    'total_hours',
                    $data
                )
            ) {

                $data['total_hours'] =
                    $data['total_hours'] !== ''
                        ? round(
                            max(
                                0,
                                (float) $data['total_hours']
                            ),
                            2
                        )
                        : null;
            }


            /*
            |--------------------------------------------------------------------------
            | Overtime Hours
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists(
                    'overtime_hours',
                    $data
                )
            ) {

                $data['overtime_hours'] =
                    round(
                        max(
                            0,
                            (float) (
                                $data['overtime_hours']
                                ?? 0
                            )
                        ),
                        2
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Financial Amounts
            |--------------------------------------------------------------------------
            */

            $baseAmount =
                array_key_exists(
                    'base_amount',
                    $data
                )
                    ? (float) $data['base_amount']
                    : (float) (
                        $workingSheet->base_amount
                        ?? 0
                    );


            $extraKmAmount =
                array_key_exists(
                    'extra_km_amount',
                    $data
                )
                    ? (float) $data['extra_km_amount']
                    : (float) (
                        $workingSheet->extra_km_amount
                        ?? 0
                    );


            $overtimeAmount =
                array_key_exists(
                    'overtime_amount',
                    $data
                )
                    ? (float) $data['overtime_amount']
                    : (float) (
                        $workingSheet->overtime_amount
                        ?? 0
                    );


            $otherAmount =
                array_key_exists(
                    'other_amount',
                    $data
                )
                    ? (float) $data['other_amount']
                    : (float) (
                        $workingSheet->other_amount
                        ?? 0
                    );


            /*
            |--------------------------------------------------------------------------
            | Prevent Negative Amounts
            |--------------------------------------------------------------------------
            */

            $baseAmount =
                max(0, $baseAmount);

            $extraKmAmount =
                max(0, $extraKmAmount);

            $overtimeAmount =
                max(0, $overtimeAmount);

            $otherAmount =
                max(0, $otherAmount);


            /*
            |--------------------------------------------------------------------------
            | Update Individual Amounts
            |--------------------------------------------------------------------------
            */

            $data['base_amount'] =
                round($baseAmount, 2);

            $data['extra_km_amount'] =
                round($extraKmAmount, 2);

            $data['overtime_amount'] =
                round($overtimeAmount, 2);

            $data['other_amount'] =
                round($otherAmount, 2);


            /*
            |--------------------------------------------------------------------------
            | Recalculate Total Amount
            |--------------------------------------------------------------------------
            */

            $data['total_amount'] =
                round(
                    $baseAmount
                    + $extraKmAmount
                    + $overtimeAmount
                    + $otherAmount,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Update Working Sheet
            |--------------------------------------------------------------------------
            */

            $workingSheet->update($data);


            /*
            |--------------------------------------------------------------------------
            | Return Fresh Model With Relationships
            |--------------------------------------------------------------------------
            */

            return $workingSheet->fresh([
                'dutySlip',
                'driver',
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

            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            |
            | WorkingSheet uses SoftDeletes.
            | No deleted_by column exists in current table.
            |
            */

            return $workingSheet->delete();
        });
    }
}