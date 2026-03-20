<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceManualLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceManualLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendances = Attendance::all()->take(5);
        $admin = User::permission('attendance management')->first() ?: User::first();

        if (!$admin || !$attendances->count()) return;

        foreach ($attendances as $att) {
            AttendanceManualLog::create([
                'attendance_id' => $att->id,
                'user_id' => $att->user_id,
                'admin_id' => $admin->id,
                'event' => 'manual_correction',
                'old_values' => ['check_in_time' => '10:55:00'],
                'new_values' => ['check_in_time' => '10:05:00'],
                'reason' => 'User had a login issue with the mobile app',
            ]);
        }
    }
}
