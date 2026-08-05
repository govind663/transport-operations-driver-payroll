<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\User\StoreUserRequest;
use App\Http\Requests\Backend\User\UpdateUserRequest;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * User Service
     */
    protected UserService $userService;

    /**
     * Constructor
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $users = $this->userService->getUsers();

        return view('backend.user.index', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('backend.user.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {

            $this->userService->store($request->validated());

            return redirect()
                ->route('users.index')
                ->with('message', 'User created successfully.');

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(string $id): View
    {
        $user = $this->userService->findById($id);

        return view('backend.user.show', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(string $id): View
    {
        $user = $this->userService->findById($id);

        return view('backend.user.edit', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        try {

            $user = $this->userService->findById($id);

            $this->userService->update(
                $user,
                $request->validated()
            );

            return redirect()
                ->route('users.index')
                ->with('message', 'User updated successfully.');

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id): RedirectResponse
    {
        try {

            $user = $this->userService->findById($id);

            $this->userService->delete($user);

            return redirect()
                ->route('users.index')
                ->with('message', 'User deleted successfully.');

        } catch (\Throwable $e) {

            return back()
                ->with('error', $e->getMessage());
        }
    }
}