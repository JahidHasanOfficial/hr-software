<?php

namespace Database\Seeders;

use App\Models\Roster;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->take(10);
        $shifts = Shift::all();
        $date = Carbon::today();

        if ($shifts->count() < 2) return;

        foreach ($users as $user) {
            // Assign a night shift to some users for next 3 days
            for ($i = 0; $i < 3; $i++) {
                $rosterDate = (clone $date)->addDays($i);
                
                Roster::query()->firstOrCreate(
                    ['user_id' => $user->id, 'date' => $rosterDate->toDateString()],
                    ['shift_id' => $shifts->last()->id]
                );
            }
        }
    }
}
