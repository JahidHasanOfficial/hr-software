<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\LeaveService;
use Spatie\Permission\Models\Role;

class DashboardService
{
    /**
     * Get Statistics for Dashboard
     */
    public function getStatistics()
    {
        $attendanceService = app(AttendanceService::class);
        $leaveService = app(LeaveService::class);
        $user = auth()->user();
        
        // Ensure leave balance is initialized
        $leaveService->initializeUserBalances($user->id, date('Y'));

        $companyId = $user->company_id;

        $userQuery = User::query();
        if ($companyId) {
            $userQuery->where('company_id', $companyId);
        }

        // Personal Stats for current logged in user (usually for employees)
        $personalStats = [
            'present' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->count(),
            'late' => Attendance::where('user_id', $user->id)
                ->where('status', 'late')
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->count(),
            'pending_requests' => AttendanceRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'remaining_leave' => LeaveBalance::where('user_id', $user->id)
                ->where('year', date('Y'))
                ->sum('remaining_quota'),
        ];

        $myRecentLogs = Attendance::where('user_id', $user->id)
            ->with('logs')
            ->latest('date')
            ->take(5)
            ->get();

        return [
            'totalUsers' => (clone $userQuery)->count(),
            'totalRoles' => Role::count(),
            'totalSalary' => (clone $userQuery)->sum('salary'),
            'recentUsers' => (clone $userQuery)->with('designation')->latest()->take(5)->get(),
            'attendanceStats' => $attendanceService->getRealTimeDashboardStats($companyId),
            'personalStats' => $personalStats,
            'myRecentLogs' => $myRecentLogs,
        ];
    }
}


