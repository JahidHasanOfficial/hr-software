<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Sick Leave', 'code' => 'SL', 'quota' => 14, 'color' => '#ff0000', 'is_paid' => 1, 'status' => 1],
            ['name' => 'Casual Leave', 'code' => 'CL', 'quota' => 10, 'color' => '#00ff00', 'is_paid' => 1, 'status' => 1],
            ['name' => 'Earned Leave', 'code' => 'EL', 'quota' => 20, 'color' => '#0000ff', 'is_paid' => 1, 'status' => 1],
            ['name' => 'Leave Without Pay', 'code' => 'LWP', 'quota' => 0, 'color' => '#000000', 'is_paid' => 0, 'status' => 1],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
