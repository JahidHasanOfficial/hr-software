<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class AttendanceDummySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($users as $user) {
            foreach ($period as $date) {
                // Skip Fridays (Weekly Off)
                if ($date->dayOfWeek == Carbon::FRIDAY) {
                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date->format('Y-m-d'),
                        'status' => 6, // Weekly Off
                    ]);
                    continue;
                }

                // Randomly skip some days for absence
                if (rand(1, 20) == 5) continue;

                $checkIn = "09:".rand(0, 15).":00";
                $checkOut = "18:".rand(0, 30).":00";
                $status = ($checkIn > "09:10:00") ? 2 : 1; // Late or Present

                $att = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in_time' => $checkIn,
                    'check_out_time' => $checkOut,
                    'status' => $status,
                    'is_calculated' => 1,
                    'stay_minutes' => 540,
                ]);

                AttendanceLog::create([
                    'attendance_id' => $att->id,
                    'user_id' => $user->id,
                    'type' => 'check_in',
                    'time' => $checkIn,
                ]);

                AttendanceLog::create([
                    'attendance_id' => $att->id,
                    'user_id' => $user->id,
                    'type' => 'check_out',
                    'time' => $checkOut,
                ]);
            }
        }
    }
}
