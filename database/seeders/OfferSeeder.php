<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Offer;
use App\Models\Candidate;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidate = Candidate::where('status', 'active')->first();

        if ($candidate) {
            Offer::create([
                'candidate_id' => $candidate->id,
                'offered_salary' => 145000,
                'joining_date' => now()->addMonth(),
                'status' => 'sent',
            ]);
        }
    }
}
