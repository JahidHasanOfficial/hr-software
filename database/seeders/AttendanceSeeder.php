<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $date = Carbon::today();

        foreach ($users as $user) {
            // Seed 10 days of attendance
            for ($i = 0; $i < 10; $i++) {
                $currentDate = (clone $date)->subDays($i);
                
                // Randomly skip some days (weekends or leave simulation)
                if (in_array($currentDate->dayOfWeek, [0, 6])) continue;

                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $currentDate->toDateString(),
                    'check_in_time' => '09:' . rand(0, 30) . ':00',
                    'check_out_time' => '18:' . rand(0, 30) . ':00',
                    'check_in_ip' => '127.0.0.1',
                    'status' => rand(1, 2), // 1=Present, 2=Late
                    'stay_minutes' => 540 + rand(0, 60),
                    'late_minutes' => rand(0, 30),
                    'early_leaving_minutes' => rand(0, 30),
                    'overtime_minutes' => rand(0, 60),
                    'notes' => 'Present',
                ]);
            }
        }
    }
}
