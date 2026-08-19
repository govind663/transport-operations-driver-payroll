<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [

            [
                'expense_code' => 'EXP001',
                'name' => 'Fuel Expense',
                'description' => 'Fuel expense for company vehicles and official transportation.',
                'expense_type' => Expense::TYPE_FUEL,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

            [
                'expense_code' => 'EXP002',
                'name' => 'Toll Expense',
                'description' => 'Toll charges incurred during official vehicle travel.',
                'expense_type' => Expense::TYPE_TOLL,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

            [
                'expense_code' => 'EXP003',
                'name' => 'Parking Expense',
                'description' => 'Parking charges incurred during official duties.',
                'expense_type' => Expense::TYPE_PARKING,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

            [
                'expense_code' => 'EXP004',
                'name' => 'Food Expense',
                'description' => 'Food and meal expenses incurred during official travel or duties.',
                'expense_type' => Expense::TYPE_FOOD,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

            [
                'expense_code' => 'EXP005',
                'name' => 'Vehicle Maintenance',
                'description' => 'Regular maintenance and servicing expenses for company vehicles.',
                'expense_type' => Expense::TYPE_MAINTENANCE,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

            [
                'expense_code' => 'EXP006',
                'name' => 'Vehicle Repair',
                'description' => 'Repair and breakdown expenses for company vehicles.',
                'expense_type' => Expense::TYPE_REPAIR,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

            [
                'expense_code' => 'EXP007',
                'name' => 'Miscellaneous Expense',
                'description' => 'Other official expenses that do not fall under a specific category.',
                'expense_type' => Expense::TYPE_MISCELLANEOUS,
                'amount' => 0.00,
                'status' => Expense::STATUS_ACTIVE,
            ],

        ];


        foreach ($expenses as $expense) {

            Expense::updateOrCreate(
                [
                    'expense_code' => $expense['expense_code'],
                ],
                $expense
            );
        }
    }
}