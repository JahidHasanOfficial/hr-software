<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers(10, request('search'));
        return view('backend.pages.users.index', compact('users'));
    }

    public function create()
    {
        $data = $this->userService->getFormData();
        return view('backend.pages.users.create', $data);
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->userService->createUser($request->validated());
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $data = $this->userService->getFormData();
        $data['user'] = $user;
        $data['userRoles'] = $user->roles->pluck('name')->toArray();
        return view('backend.pages.users.edit', $data);
    }

    public function show(User $user)
    {
        $user->load(['company', 'branch', 'department', 'designation', 'shift', 'roles']);
        return view('backend.pages.users.show', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $this->userService->updateUser($user, $request->validated());
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete user.');
        }
    }
}
