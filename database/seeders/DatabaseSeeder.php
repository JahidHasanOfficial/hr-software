<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            ShiftSeeder::class,
            OrganizationSeeder::class,
            SalaryComponentSeeder::class,
            LeaveTypeSeeder::class,
            EmployeeSeeder::class,
            AttendanceDummySeeder::class,
            LeaveDummySeeder::class,
            PayrollDummySeeder::class,
        ]);
    }
}
