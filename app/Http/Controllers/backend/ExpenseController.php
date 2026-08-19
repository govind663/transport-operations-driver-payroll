<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Expense\StoreExpenseRequest;
use App\Http\Requests\Backend\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\Expense\ExpenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Expense Service
    |--------------------------------------------------------------------------
    */

    protected ExpenseService $expenseService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        ExpenseService $expenseService
    ) {
        $this->expenseService = $expenseService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of expenses.
     */
    public function index(): View
    {
        $expenses = $this->expenseService
            ->getExpenses();

        return view('backend.expenses.index',
            compact('expenses')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new expense.
     */
    public function create(): View
    {
        return view('backend.expenses.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created expense.
     */
    public function store(
        StoreExpenseRequest $request
    ): RedirectResponse {

        $this->expenseService->store(
            $request->validated()
        );

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified expense.
     */
    public function show(
        Expense $expense
    ): View {

        $expense = $this->expenseService
            ->findById($expense->id);

        return view('backend.expenses.show',
            compact('expense')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(
        Expense $expense
    ): View {

        $expense = $this->expenseService
            ->findById($expense->id);

        return view('backend.expenses.edit',
            compact('expense')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified expense.
     */
    public function update(
        UpdateExpenseRequest $request,
        Expense $expense
    ): RedirectResponse {

        $this->expenseService->update(
            $expense,
            $request->validated()
        );

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified expense.
     */
    public function destroy(
        Expense $expense
    ): RedirectResponse {

        $this->expenseService->delete(
            $expense
        );

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense deleted successfully.'
            );
    }
}