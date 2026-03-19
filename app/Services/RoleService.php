<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    /**
     * Create a new role and assign permissions.
     */
    public function createRole(array $data)
    {
        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        
        return $role;
    }

    /**
     * Update an existing role and its permissions.
     */
    public function updateRole(Role $role, array $data)
    {
        $role->update(['name' => $data['name']]);
        
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        
        return $role;
    }

    /**
     * Delete a role.
     */
    public function deleteRole(Role $role)
    {
        return $role->delete();
    }
}
