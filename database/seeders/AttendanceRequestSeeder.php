<?php

namespace Database\Seeders;

use App\Models\AttendanceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->take(5);
        $admin = User::permission('attendance management')->first() ?: User::first();
        $date = Carbon::today();

        if (!$admin) return;

        foreach ($users as $user) {
            // 1. Regularization Request
            AttendanceRequest::create([
                'user_id' => $user->id,
                'type' => 1, // Regularization
                'date' => (clone $date)->subDay()->toDateString(),
                'check_in_time' => '10:05:00',
                'check_out_time' => '18:15:00',
                'reason' => 'Forgot to punch out due to internet outage',
                'status' => 0, // Pending
            ]);

            // 2. On-Duty Request (Approved)
            AttendanceRequest::create([
                'user_id' => $user->id,
                'type' => 2, // On-Duty
                'date' => (clone $date)->subDays(2)->toDateString(),
                'reason' => 'Client meeting at Uttara branch',
                'status' => 1, // Approved
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);
        }
    }
}
