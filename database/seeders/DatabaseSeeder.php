<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShiftSeeder::class,
            RolePermissionSeeder::class,
            CompanySeeder::class,
            BranchSeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            HolidaySeeder::class,
            WeeklyOffSeeder::class,
            UserSeeder::class,
            RosterSeeder::class,
            AttendanceSeeder::class,
            AttendanceRequestSeeder::class,
            AttendanceManualLogSeeder::class,
        ]);
    }
}
