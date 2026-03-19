<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class DashboardService
{
    /**
     * Get Statistics for Dashboard
     */
    public function getStatistics()
    {
        return [
            'totalUsers' => User::count(),
            'totalRoles' => Role::count(),
            'totalSalary' => User::sum('salary'),
            'recentUsers' => User::with('designation')->latest()->take(5)->get(),
        ];
    }
}
