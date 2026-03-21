<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Scorecard;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\User;

class ScorecardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidate = Candidate::first();
        $interview = Interview::first();
        $user = User::first();

        if ($candidate && $interview && $user) {
            Scorecard::create([
                'candidate_id' => $candidate->id,
                'interview_id' => $interview->id,
                'interviewer_id' => $user->id,
                'ratings' => ['Technical' => 5, 'Comms' => 4, 'Culture' => 4],
                'overall_feedback' => 'Strong candidate with deep knowledge of Laravel 11.',
                'total_score' => 13,
            ]);
        }
    }
}
