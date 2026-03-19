<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        foreach ($departments as $department) {
            Designation::factory()->count(4)->create(['department_id' => $department->id]);
        }
    }
}
