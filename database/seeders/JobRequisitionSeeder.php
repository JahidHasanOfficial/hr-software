<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\JobRequisition;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;

class JobRequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dept = Department::first();
        $desig = Designation::where('department_id', $dept->id)->first() ?? Designation::first();
        $user = User::first();

        if ($dept && $desig && $user) {
            JobRequisition::create([
                'title' => 'Senior Laravel Developer',
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'headcount' => 2,
                'urgency_level' => 'high',
                'justification' => 'Immediate need for core architecture migration.',
                'budget_details' => '120k - 160k BDT',
                'status' => 'approved',
                'requested_by' => $user->id,
            ]);

            JobRequisition::create([
                'title' => 'UI/UX Lead',
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'headcount' => 1,
                'urgency_level' => 'medium',
                'justification' => 'To strengthen frontend ecosystem.',
                'status' => 'approved',
                'requested_by' => $user->id,
            ]);
        }
    }
}
