<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\WeeklyOff;
use Illuminate\Database\Seeder;

class WeeklyOffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        $weeklyOffs = [
            ['day' => 5], // 5 = Friday in Carbon (0-6 starting from Sunday)
            ['day' => 6], // 6 = Saturday
        ];

        foreach ($weeklyOffs as $off) {
            WeeklyOff::query()->firstOrCreate(
                ['company_id' => $company->id, 'day' => $off['day']],
                $off
            );
        }
    }
}
