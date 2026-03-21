<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Interview;
use App\Models\Candidate;
use App\Models\User;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidate = Candidate::first();
        $user = User::first();

        if ($candidate && $user) {
            $int = Interview::create([
                'candidate_id' => $candidate->id,
                'title' => 'Technical Round 1',
                'scheduled_at' => now()->addDays(2)->setHour(10)->setMinute(0),
                'location' => 'Board Room / Online',
                'interview_type' => 'technical',
                'status' => 'scheduled',
            ]);

            $int->interviewers()->sync([$user->id]);
        }
    }
}
