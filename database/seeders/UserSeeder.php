<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 HR Managers
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('HR Manager');
        });

        // Create 20 Employees
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole('Employee');
        });
    }
}
