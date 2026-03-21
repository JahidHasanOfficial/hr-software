<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CandidateStage;

class CandidateStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            ['name' => 'Applied', 'order' => 1],
            ['name' => 'Phone Screening', 'order' => 2],
            ['name' => 'Technical Interview', 'order' => 3],
            ['name' => 'HR Interview', 'order' => 4],
            ['name' => 'Offer Sent', 'order' => 5],
            ['name' => 'Hired', 'order' => 6],
            ['name' => 'Rejected', 'order' => 100],
        ];

        foreach ($stages as $stage) {
            CandidateStage::updateOrCreate(['name' => $stage['name']], $stage);
        }
    }
}
