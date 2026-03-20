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
                $shift = $user->getEffectiveShift();
                $status = 1; // Default: Present
                $lateMinutes = 0;

                if ($shift && !$shift->is_flexible) {
                    $startTime = Carbon::parse($today . ' ' . $shift->start_time);
                    $lateLimit = $startTime->copy()->addMinutes($shift->late_threshold);
                    
                    if ($currentTime->gt($startTime)) {
                        $lateMinutes = $startTime->diffInMinutes($currentTime);
                        
                        // Change status only if late threshold (grace period) is exceeded
                        if ($currentTime->gt($lateLimit)) {
                            $status = 2; // 2 = Late
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
                $shift = $user->getEffectiveShift();
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
                    $attendance->status = 3; // Half Day
                }
            } else {
                // For flexible shifts, maybe OT is after 9 hours (540 mins)
                if ($stayMinutes > 540) {
                    $overtimeMinutes = $stayMinutes - 540;
                }
            }

            $attendance->update([
                'check_out_time' => $checkoutTime->toTimeString(),
                'check_out_latitude' => $data['latitude'] ?? null,
                'check_out_longitude' => $data['longitude'] ?? null,
                'check_out_ip' => $data['ip'] ?? null,
                'stay_minutes' => $stayMinutes,
                'early_leaving_minutes' => $earlyLeavingMinutes,
                'overtime_minutes' => $overtimeMinutes,
                'status' => $attendance->status, // Might have changed to Half Day
            ]);

            return $attendance;
        });
    }

    /**
     * Get Paginated All Attendance for Admin
     */
    public function getAllAttendance($perPage = 15)
    {
        return Attendance::with(['user.shift', 'user.department.shift', 'user.branch.shift', 'logs'])
            ->orderBy('date', 'desc')
            ->paginate($perPage);
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
