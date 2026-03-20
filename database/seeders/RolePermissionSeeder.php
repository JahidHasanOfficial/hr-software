<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define ALL granular permissions
        $permissions = [
            'dashboard access',

            // User Management 
            'user.index', 'user.create', 'user.store', 'user.show', 'user.edit', 'user.update', 'user.destroy',

            // Role Management
            'role.index', 'role.create', 'role.store', 'role.show', 'role.edit', 'role.update', 'role.destroy',

            // Company
            'company.index', 'company.create', 'company.store', 'company.show', 'company.edit', 'company.update', 'company.destroy',

            // Branch
            'branch.index', 'branch.create', 'branch.store', 'branch.show', 'branch.edit', 'branch.update', 'branch.destroy',

            // Department
            'department.index', 'department.create', 'department.store', 'department.show', 'department.edit', 'department.update', 'department.destroy',

            // Designation
            'designation.index', 'designation.create', 'designation.store', 'designation.show', 'designation.edit', 'designation.update', 'designation.destroy',

            // Shifts
            'shift.index', 'shift.create', 'shift.store', 'shift.edit', 'shift.update', 'shift.destroy',

            // Attendance
            'attendance.index', 'attendance.check_in', 'attendance.check_out', 'attendance.logs',

            // Broad Categories
            'employee management',
            'role permission management',
            'organization management',
            'attendance management', // Admin/HR Category

            // HR Functions
            'payroll management',
            'leave management',
            
            // Profile
            'profile.edit', 'profile.update', 'profile.destroy',

            // New Attendance Rules & Features
            'holiday.index', 'holiday.create', 'holiday.store', 'holiday.edit', 'holiday.update', 'holiday.destroy',
            'weekly_off.index', 'weekly_off.create', 'weekly_off.store', 'weekly_off.edit', 'weekly_off.update', 'weekly_off.destroy',
            'roster.index', 'roster.create', 'roster.store', 'roster.edit', 'roster.update', 'roster.destroy',
            'attendance_request.index', 'attendance_request.create', 'attendance_request.store', 'attendance_request.approve', 'attendance_request.reject',

            // New Granular Dashboard Card Permissions
            'dashboard.real_time_overview',
            'dashboard.statistics_cards',
            'dashboard.recent_employees',
            'dashboard.employee_personal_stats',

            // New Leave Management Permissions
            'leave_type.index', 'leave_type.create', 'leave_type.store', 'leave_type.edit', 'leave_type.update', 'leave_type.destroy',
            'leave.index', 'leave.create', 'leave.store', 'leave.show', 'leave.edit', 'leave.update', 'leave.destroy', 'leave.approve', 'leave.reject',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 1. Admin (Everything)
        $adminRole = Role::findOrCreate('Admin');
        $adminRole->syncPermissions(Permission::all());

        // 2. HR Manager
        $hrManagerRole = Role::findOrCreate('HR Manager');
        $hrManagerRole->syncPermissions([
            'dashboard access',
            'employee management',
            'user.index', 'user.create', 'user.store', 'user.show', 'user.edit', 'user.update', 'user.destroy',
            'organization management',
            'company.index', 'company.create', 'company.store', 'company.show', 'company.edit', 'company.update', 'company.destroy',
            'branch.index', 'branch.create', 'branch.store', 'branch.show', 'branch.edit', 'branch.update', 'branch.destroy',
            'department.index', 'department.create', 'department.store', 'department.show', 'department.edit', 'department.update', 'department.destroy',
            'designation.index', 'designation.create', 'designation.store', 'designation.show', 'designation.edit', 'designation.update', 'designation.destroy',
            'attendance management',
            'shift.index', 'shift.create', 'shift.store', 'shift.edit', 'shift.update', 'shift.destroy',
            'attendance.index', 'attendance.check_in', 'attendance.check_out', 'attendance.logs',
            'holiday.index', 'holiday.create', 'holiday.store', 'holiday.edit', 'holiday.update', 'holiday.destroy',
            'weekly_off.index', 'weekly_off.create', 'weekly_off.store', 'weekly_off.edit', 'weekly_off.update', 'weekly_off.destroy',
            'roster.index', 'roster.create', 'roster.store', 'roster.edit', 'roster.update', 'roster.destroy',
            'attendance_request.index', 'attendance_request.create', 'attendance_request.store', 'attendance_request.approve', 'attendance_request.reject',
            'payroll management',
            'leave management',
            'dashboard.real_time_overview',
            'dashboard.statistics_cards',
            'dashboard.recent_employees',
            // Leave Management HR
            'leave_type.index', 'leave_type.create', 'leave_type.store', 'leave_type.edit', 'leave_type.update', 'leave_type.destroy',
            'leave.index', 'leave.show', 'leave.approve', 'leave.reject',
        ]);

        // 3. Employee
        $employeeRole = Role::findOrCreate('Employee');
        $employeeRole->syncPermissions([
            'dashboard access',
            'dashboard.employee_personal_stats',
            'attendance.check_in', 
            'attendance.check_out', 
            'attendance.logs',
            'roster.index', 
            'attendance_request.index', 
            'attendance_request.create',
            'profile.edit',
            'profile.update',
            // Leave Management Employee
            'leave.index', 'leave.create', 'leave.store', 'leave.show',
        ]);

        // --- Users ---
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin', 
                'password' => Hash::make('password'), 
                'status' => 1,
                'shift_id' => Shift::inRandomOrder()->first()->id ?? null
            ]
        );
        $admin->syncRoles([$adminRole]);

        $hr = User::updateOrCreate(
            ['email' => 'hr@gmail.com'],
            [
                'name' => 'HR Manager', 
                'password' => Hash::make('password'), 
                'status' => 1,
                'shift_id' => Shift::inRandomOrder()->first()->id ?? null
            ]
        );
        $hr->syncRoles([$hrManagerRole]);
    }
}
