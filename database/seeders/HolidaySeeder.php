<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        $year = now()->year;

        $holidays = [
            ['name' => 'International Mother Language Day', 'date' => $year . '-02-21', 'is_recurring' => true],
            ['name' => 'Independence Day', 'date' => $year . '-03-26', 'is_recurring' => true],
            ['name' => 'Pohela Boishakh', 'date' => $year . '-04-14', 'is_recurring' => true],
            ['name' => 'May Day', 'date' => $year . '-05-01', 'is_recurring' => true],
            ['name' => 'Victory Day', 'date' => $year . '-12-16', 'is_recurring' => true],
            ['name' => 'Christmas Day', 'date' => $year . '-12-25', 'is_recurring' => true],
        ];

        foreach ($holidays as $holiday) {
            Holiday::query()->firstOrCreate(
                ['company_id' => $company->id, 'date' => $holiday['date']],
                $holiday
            );
        }
    }
}
