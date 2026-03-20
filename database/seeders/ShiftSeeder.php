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
        $shifts = [
            [
                'name' => 'Morning Shift',
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'late_threshold' => 15,
                'early_checkout_threshold' => 15,
                'break_time' => 60,
                'half_day_threshold' => 240,
                'is_flexible' => false,
                'status' => 1,
            ],
            [
                'name' => 'Evening Shift',
                'start_time' => '18:00:00',
                'end_time' => '02:00:00',
                'late_threshold' => 15,
                'early_checkout_threshold' => 15,
                'break_time' => 60,
                'half_day_threshold' => 240,
                'is_flexible' => false,
                'status' => 1,
            ],
            [
                'name' => 'Night Shift',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'late_threshold' => 15,
                'early_checkout_threshold' => 15,
                'break_time' => 60,
                'half_day_threshold' => 240,
                'is_flexible' => false,
                'status' => 1,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::query()->firstOrCreate(
                ['name' => $shift['name']],
                $shift
            );
        }
    }
}