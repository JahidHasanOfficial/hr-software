<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Hash;

class UserService
{
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
            'designation' => $data['designation'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'salary' => $data['salary'] ?? null,
            'status' => $data['status'] ?? 'active',
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
            'designation' => $data['designation'] ?? $user->designation,
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
