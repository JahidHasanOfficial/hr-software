<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\JobPost;
use App\Models\JobRequisition;
use Illuminate\Support\Str;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requisitions = JobRequisition::where('status', 'approved')->get();

        foreach ($requisitions as $req) {
            JobPost::create([
                'job_requisition_id' => $req->id,
                'job_code' => 'JOB-' . date('Y') . '-' . strtoupper(Str::random(5)),
                'title' => $req->title,
                'description' => 'Detailed Job Description for ' . $req->title . '. Looking for experienced professionals.',
                'employment_type' => 'full-time',
                'location' => 'Dhaka',
                'salary_min' => 100000,
                'salary_max' => 150000,
                'expiry_date' => now()->addMonth(),
                'is_published' => true,
            ]);
        }
    }
}
