<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreRoleRequest;
use App\Http\Requests\Backend\UpdateRoleRequest;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('backend.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('backend.pages.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->roleService->createRole($request->validated());
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        return view('backend.pages.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('backend.pages.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        DB::beginTransaction();
        try {
            $this->roleService->updateRole($role, $request->validated());
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Admin') {
            return back()->with('error', 'Admin role cannot be deleted.');
        }

        try {
            $this->roleService->deleteRole($role);
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete role.');
        }
    }
}
