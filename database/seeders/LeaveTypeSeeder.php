<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Casual Leave',
                'code' => 'CL',
                'quota' => 12,
                'is_accruable' => false,
                'requires_attachment' => false,
                'status' => 1,
            ],
            [
                'name' => 'Sick Leave',
                'code' => 'SL',
                'quota' => 8,
                'is_accruable' => false,
                'requires_attachment' => true,
                'status' => 1,
            ],
            [
                'name' => 'Earned Leave',
                'code' => 'EL',
                'quota' => 15,
                'is_accruable' => true,
                'status' => 1,
                'requires_attachment' => false,
            ],
        ];

        foreach ($types as $type) {
            \App\Models\LeaveType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}
