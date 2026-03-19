<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;
use Spatie\Permission\Models\Role;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get Paginated Users for Index
     */
    public function getAllUsers($perPage = 10)
    {
        return User::with(['company', 'branch', 'department', 'designation', 'shift', 'roles'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get All Data Required for User Forms (Create/Edit)
     */
    public function getFormData()
    {
        return [
            'roles' => Role::all(),
            'companies' => Company::where('status', 1)->get(),
            'branches' => Branch::where('status', 1)->get(),
            'departments' => Department::where('status', 1)->get(),
            'designations' => Designation::where('status', 1)->get(),
            'shifts' => Shift::where('status', 1)->get(),
        ];
    }

    /**
     * Create a new user with basic HR details and assign roles.
     */
    public function createUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'designation_id' => $data['designation_id'] ?? null,
            'shift_id' => $data['shift_id'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'salary' => $data['salary'] ?? null,
            'status' => $data['status'] ?? 1,
        ];

        if (isset($data['image'])) {
            $userData['image'] = ImageHelper::upload($data['image'], 'users');
        }

        $user = User::create($userData);

        if (isset($data['roles'])) {
            $user->assignRole($data['roles']);
        }

        return $user;
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data)
    {
        $userData = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'company_id' => $data['company_id'] ?? $user->company_id,
            'branch_id' => $data['branch_id'] ?? $user->branch_id,
            'department_id' => $data['department_id'] ?? $user->department_id,
            'designation_id' => $data['designation_id'] ?? $user->designation_id,
            'shift_id' => $data['shift_id'] ?? $user->shift_id,
            'joining_date' => $data['joining_date'] ?? $user->joining_date,
            'salary' => $data['salary'] ?? $user->salary,
            'status' => $data['status'] ?? $user->status,
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        if (isset($data['image'])) {
            $userData['image'] = ImageHelper::update($data['image'], 'users', $user->image);
        }

        $user->update($userData);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    /**
     * Delete a user and their image.
     */
    public function deleteUser(User $user)
    {
        if ($user->image) {
            ImageHelper::delete($user->image);
        }
        return $user->delete();
    }
}
