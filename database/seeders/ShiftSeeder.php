<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::create([
            'name' => 'Morning Shift',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'late_threshold' => 15,
            'status' => 1,
        ]);

        Shift::create([
            'name' => 'Night Shift',
            'start_time' => '21:00:00',
            'end_time' => '06:00:00',
            'late_threshold' => 10,
            'status' => 1,
        ]);
    }
}
