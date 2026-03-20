<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    /**
     * Get Leave Balance for a user
     */
    public function getUserBalance($userId, $year = null)
    {
        $year = $year ?: date('Y');
        
        // Ensure balances are initialized for this user
        $this->initializeUserBalances($userId, $year);

        return LeaveBalance::where('user_id', $userId)
            ->where('year', $year)
            ->with('leaveType')
            ->get();
    }

    /**
     * Initialize balances for a user if they don't exist
     */
    public function initializeUserBalances($userId, $year)
    {
        $activeTypes = LeaveType::where('status', 1)->get();

        foreach ($activeTypes as $type) {
            LeaveBalance::firstOrCreate(
                [
                    'user_id' => $userId,
                    'leave_type_id' => $type->id,
                    'year' => $year
                ],
                [
                    'total_quota' => $type->quota,
                    'used_quota' => 0,
                    'remaining_quota' => $type->quota
                ]
            );
        }
    }

    /**
     * Apply for Leave
     */
    public function applyLeave($data)
    {
        return DB::transaction(function () use ($data) {
            $startDate = Carbon::parse($data['start_date']);
            $endDate = ($data['day_type'] == 'full') ? Carbon::parse($data['end_date']) : $startDate;
            
            // Calculate total days (simplified, excluding weekends can be added in Phase 2)
            $totalDays = $data['total_days'] ?? ($startDate->diffInDays($endDate) + 1);

            // If half day FH/SH
            if ($data['day_type'] != 'full') {
                $totalDays = 0.5;
            }

            // check balance
            $balance = LeaveBalance::where('user_id', $data['user_id'])
                ->where('leave_type_id', $data['leave_type_id'])
                ->where('year', $startDate->year)
                ->first();

            if (!$balance || $balance->remaining_quota < $totalDays) {
                throw new \Exception("Insufficient leave balance.");
            }

            $leave = Leave::create([
                'user_id' => $data['user_id'],
                'leave_type_id' => $data['leave_type_id'],
                'start_date' => $data['start_date'],
                'end_date' => ($data['day_type'] == 'full') ? $data['end_date'] : $data['start_date'],
                'total_days' => $totalDays,
                'day_type' => $data['day_type'],
                'reason' => $data['reason'],
                'attachment' => $data['attachment'] ?? null,
                'status' => 'pending'
            ]);

            return $leave;
        });
    }

    /**
     * Approve Leave
     */
    public function approveLeave($leaveId, $approvedBy)
    {
        return DB::transaction(function () use ($leaveId, $approvedBy) {
            $leave = Leave::findOrFail($leaveId);
            if ($leave->status != 'pending') return $leave;

            $leave->update([
                'status' => 'approved',
                'approved_by' => $approvedBy
            ]);

            // Deduct from balance
            $balance = LeaveBalance::where('user_id', $leave->user_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', Carbon::parse($leave->start_date)->year)
                ->first();

            if ($balance) {
                $balance->used_quota += $leave->total_days;
                $balance->remaining_quota -= $leave->total_days;
                $balance->save();
            }

            return $leave;
        });
    }

    /**
     * Reject Leave
     */
    public function rejectLeave($leaveId, $reason)
    {
        $leave = Leave::findOrFail($leaveId);
        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
        return $leave;
    }
}
