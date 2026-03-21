<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Candidate;
use App\Models\JobPost;
use App\Models\CandidateStage;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = JobPost::first();
        $st_applied = CandidateStage::where('name', 'Applied')->first();
        $st_tech = CandidateStage::where('name', 'Technical Interview')->first();

        if ($post && $st_applied && $st_tech) {
            Candidate::create([
                'job_post_id' => $post->id,
                'candidate_stage_id' => $st_tech->id,
                'first_name' => 'Jahid',
                'last_name' => 'Hasan',
                'email' => 'jahid@example.com',
                'phone' => '01700000000',
                'source' => 'LinkedIn',
                'experience_years' => 5,
                'expected_salary' => 140000,
                'status' => 'active',
            ]);

            Candidate::create([
                'job_post_id' => $post->id,
                'candidate_stage_id' => $st_applied->id,
                'first_name' => 'Sara',
                'last_name' => 'Islam',
                'email' => 'sara@example.com',
                'phone' => '01800000000',
                'source' => 'Web Site',
                'experience_years' => 3,
                'expected_salary' => 110000,
                'status' => 'active',
            ]);
        }
    }
}
