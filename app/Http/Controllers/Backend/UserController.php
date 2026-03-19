<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Services\UserService;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
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
        $users = User::with(['company', 'branch', 'department', 'designationRel'])->latest()->paginate(10);
        return view('backend.pages.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $companies = Company::where('status', 'active')->get();
        $branches = Branch::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        return view('backend.pages.users.create', compact('roles', 'companies', 'branches', 'departments', 'designations'));
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
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $companies = Company::where('status', 'active')->get();
        $branches = Branch::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        return view('backend.pages.users.edit', compact('user', 'roles', 'userRoles', 'companies', 'branches', 'departments', 'designations'));
    }

    public function show(User $user)
    {
        $user->load(['company', 'branch', 'department', 'designationRel']);
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
