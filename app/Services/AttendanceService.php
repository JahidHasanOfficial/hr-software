<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Handle Employee Check-in
     */
    public function handleCheckIn(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $today = Carbon::today()->toDateString();
            $currentTime = Carbon::now();
            
            // Check if first attendance of the day
            $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();
            
            if (!$attendance) {
                // Check if it's a Weekly Off or Holiday
                $shift = $user->getEffectiveShift($today);
                $status = 1; // Default: Present

                if ($this->isHoliday($user, $today)) {
                    $status = 5; // Special: Present on Holiday
                } elseif ($this->isWeeklyOff($user, $today)) {
                    $status = 6; // Special: Present on Weekly Off
                }

                $lateMinutes = 0;

                if ($shift && !$shift->is_flexible) {
                    $startTime = Carbon::parse($today . ' ' . $shift->start_time);
                    $lateLimit = $startTime->copy()->addMinutes($shift->late_threshold);
                    
                    if ($currentTime->gt($startTime)) {
                        $lateMinutes = $startTime->diffInMinutes($currentTime);
                        
                        // Change status only if late threshold (grace period) is exceeded
                        if ($currentTime->gt($lateLimit)) {
                            // Only mark as late if not a special status (Holiday/Weekly Off)
                            if ($status == 1) $status = 2; // Late
                        }
                    }
                }

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'shift_id' => $shift ? $shift->id : null,
                    'date' => $today,
                    'check_in_time' => $currentTime->toTimeString(),
                    'check_in_latitude' => $data['latitude'] ?? null,
                    'check_in_longitude' => $data['longitude'] ?? null,
                    'check_in_ip' => $data['ip'] ?? null,
                    'device_info' => request()->header('User-Agent'),
                    'late_minutes' => $lateMinutes,
                    'status' => $status,
                ]);
            }

            // Always create a detailed log record
            return AttendanceLog::create([
                'attendance_id' => $attendance->id,
                'user_id' => $user->id,
                'type' => 'check_in',
                'time' => $currentTime->toTimeString(),
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'ip' => $data['ip'] ?? null,
            ]);
        });
    }

    /**
     * Handle Employee Check-out
     */
    public function handleCheckOut(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $today = Carbon::today()->toDateString();
            $checkoutTime = Carbon::now();

            $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();
            
            if (!$attendance) {
                throw new \Exception('First check-in is required today.');
            }

            // Always create log
            AttendanceLog::create([
                'attendance_id' => $attendance->id,
                'user_id' => $user->id,
                'type' => 'check_out',
                'time' => $checkoutTime->toTimeString(),
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'ip' => $data['ip'] ?? null,
            ]);

            // Final Stats Calculation
            $checkinTime = Carbon::parse($today . ' ' . $attendance->check_in_time);
            $totalMinutes = $checkinTime->diffInMinutes($checkoutTime);
            
            $shift = $attendance->shift; // Use the shift stored at check-in
            if (!$shift) {
                $shift = $user->getEffectiveShift($today);
            }

            $earlyLeavingMinutes = 0;
            $overtimeMinutes = 0;
            $stayMinutes = $totalMinutes;

            if ($shift && !$shift->is_flexible) {
                $shiftEndTime = Carbon::parse($today . ' ' . $shift->end_time);
                
                // Early Leaving
                if ($checkoutTime->lt($shiftEndTime)) {
                    $diff = $checkoutTime->diffInMinutes($shiftEndTime);
                    if ($diff > $shift->early_checkout_threshold) {
                        $earlyLeavingMinutes = $diff;
                    }
                } 
                // Overtime
                elseif ($checkoutTime->gt($shiftEndTime)) {
                    $overtimeMinutes = $shiftEndTime->diffInMinutes($checkoutTime);
                }

                // Deduct break time if stay is longer than break time
                if ($stayMinutes > ($shift->break_time + 60)) {
                    $stayMinutes -= $shift->break_time;
                }

                // Check for Half Day
                if ($stayMinutes < $shift->half_day_threshold) {
                    $attendance->status = 3; 
                }
            } else {
                // Flexible shift logic...
                if ($stayMinutes > 540) {
                    $overtimeMinutes = $stayMinutes - 540;
                }
            }

            // Comp-off Earner Logic
            if ($attendance->status == 5 || $attendance->status == 6) {
                // User worked on Holiday/Weekly Off - can trigger comp-off credit here
            }

            $attendance->update([
                'check_out_time' => $checkoutTime->toTimeString(),
                'check_out_latitude' => $data['latitude'] ?? null,
                'check_out_longitude' => $data['longitude'] ?? null,
                'check_out_ip' => $data['ip'] ?? null,
                'stay_minutes' => $stayMinutes,
                'early_leaving_minutes' => $earlyLeavingMinutes,
                'overtime_minutes' => $overtimeMinutes,
                'status' => $attendance->status,
            ]);

            return $attendance;
        });
    }

    /**
     * Check if a date is a Holiday for the user
     */
    public function isHoliday(User $user, $date)
    {
        return \App\Models\Holiday::where('company_id', $user->company_id)
            ->where('date', $date)
            ->exists();
    }

    /**
     * Check if a date is a Weekly Off for the user
     */
    public function isWeeklyOff(User $user, $date)
    {
        $dayNum = Carbon::parse($date)->dayOfWeek; // 0=Sunday
        
        return \App\Models\WeeklyOff::where('company_id', $user->company_id)
            ->where(function ($query) use ($user) {
                $query->whereNull('branch_id')->orWhere('branch_id', $user->branch_id);
            })
            ->where(function ($query) use ($user) {
                $query->whereNull('department_id')->orWhere('department_id', $user->department_id);
            })
            ->where('day', $dayNum)
            ->exists();
    }

    /**
     * Handle Manual Correction by Admin
     */
    public function handleManualCorrection(User $admin, User $employee, array $data)
    {
        return DB::transaction(function () use ($admin, $employee, $data) {
            $attendance = Attendance::updateOrCreate(
                ['user_id' => $employee->id, 'date' => $data['date']],
                [
                    'check_in_time' => $data['check_in_time'] ?? null,
                    'check_out_time' => $data['check_out_time'] ?? null,
                    'notes' => $data['reason'],
                    'status' => $data['status'] ?? 1,
                ]
            );

            // Audit Log
            \App\Models\AttendanceManualLog::create([
                'attendance_id' => $attendance->id,
                'user_id' => $employee->id,
                'admin_id' => $admin->id,
                'event' => 'manual_correction',
                'new_values' => $data,
                'reason' => $data['reason'],
            ]);

            return $attendance;
        });
    }

    /**
     * Real-time Attendance Stats for Dashboard
     */
    public function getRealTimeDashboardStats($companyId = null)
    {
        $today = Carbon::today()->toDateString();
        
        $userQuery = User::where('status', 1);
        if ($companyId) {
            $userQuery->where('company_id', $companyId);
        }
        $totalEmployees = $userQuery->count();

        $attQuery = Attendance::where('date', $today);
        if ($companyId) {
            $attQuery->whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }
        
        $presentToday = (clone $attQuery)->whereIn('status', [1, 2, 3, 5, 6])->count();
        $lateToday = (clone $attQuery)->where('status', 2)->count();
        $activeNow = (clone $attQuery)->whereNotNull('check_in_time')->whereNull('check_out_time')->count();

        // Leave Overview
        $leaveQuery = \App\Models\Leave::query();
        if ($companyId) {
            $leaveQuery->whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }
        
        $onLeaveToday = (clone $leaveQuery)->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $pendingLeaves = (clone $leaveQuery)->where('status', 'pending')->count();
        $approvedLeaves = (clone $leaveQuery)->where('status', 'approved')->whereYear('start_date', date('Y'))->count();
        $rejectedLeaves = (clone $leaveQuery)->where('status', 'rejected')->whereYear('start_date', date('Y'))->count();

        return [
            'total_employees' => $totalEmployees,
            'present' => $presentToday,
            'late' => $lateToday,
            'on_leave' => $onLeaveToday,
            'absent' => max(0, $totalEmployees - $presentToday - $onLeaveToday),
            'attendance_rate' => $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0,
            'active_now' => $activeNow,
            'pending_leaves' => $pendingLeaves,
            'approved_leaves' => $approvedLeaves,
            'rejected_leaves' => $rejectedLeaves,
        ];
    }

    /**
     * Get Paginated All Attendance for Admin
     */
    public function getAllAttendance($perPage = 15, $search = null)
    {
        $query = Attendance::with(['user.shift', 'user.department.shift', 'user.branch.shift', 'logs']);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })->orWhere('date', 'LIKE', "%{$search}%");
        }

        return $query->orderBy('date', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get Attendance Logs for Specific User
     */
    public function getUserLogs($userId, $perPage = 10)
    {
        return Attendance::where('user_id', $userId)
            ->with(['logs', 'user.shift', 'user.department.shift', 'user.branch.shift'])
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }
}
