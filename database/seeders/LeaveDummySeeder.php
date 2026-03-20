<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveDummySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', '!=', 'admin@hr.test')->get();
        $types = LeaveType::all();

        foreach ($users as $user) {
            // Add 1-2 approved leaves in the past
            for ($i = 0; $i < rand(1, 2); $i++) {
                $start = Carbon::now()->subDays(rand(10, 25));
                Leave::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $types->random()->id,
                    'start_date' => $start->toDateString(),
                    'end_date' => $start->addDays(rand(0, 2))->toDateString(),
                    'total_days' => rand(1, 3),
                    'status' => 'approved',
                    'reason' => 'Previous personal engagement.',
                ]);
            }

            // Add 1 pending leave
            Leave::create([
                'user_id' => $user->id,
                'leave_type_id' => $types->random()->id,
                'start_date' => Carbon::now()->addDays(rand(1, 5))->toDateString(),
                'total_days' => 1,
                'status' => 'pending',
                'reason' => 'Applying for next week.',
            ]);
        }
    }
}
