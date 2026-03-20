<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Show Attendance Summary Report
     */
    public function summary(Request $request)
    {
        $this->authorize('attendance.report');

        $branches = Branch::all();
        $departments = Department::all();
        $users = User::where('status', 1)->get();

        $filters = [
            'start_date' => $request->start_date ?? date('Y-m-01'),
            'end_date' => $request->end_date ?? date('Y-m-d'),
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'branch_id' => $request->branch_id,
        ];

        $report = $this->attendanceService->getAttendanceReport($filters);

        return view('backend.pages.reports.attendance_summary', compact('report', 'branches', 'departments', 'users', 'filters'));
    }

    /**
     * Show Detailed Daily Attendance Report
     */
    public function detailed(Request $request)
    {
        $this->authorize('attendance.report');

        $branches = Branch::all();
        $departments = Department::all();
        $users = User::where('status', 1)->get();

        $filters = [
            'start_date' => $request->start_date ?? date('Y-m-d'),
            'end_date' => $request->end_date ?? date('Y-m-d'),
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'branch_id' => $request->branch_id,
        ];

        $report = $this->attendanceService->getAttendanceReport($filters);

        return view('backend.pages.reports.attendance_detailed', compact('report', 'branches', 'departments', 'users', 'filters'));
    }
}
