<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $attendances = $this->attendanceService->getAllAttendance(15, request('search'));
        return view('backend.pages.attendances.index', compact('attendances'));
    }

    /**
     * Employee Daily Check-in
     */
    public function checkIn(Request $request)
    {
        try {
            $data = $request->only(['latitude', 'longitude']);
            $data['ip'] = $request->ip();
            $user = Auth::user();

            $this->attendanceService->handleCheckIn($user, $data);
            return back()->with('success', 'Check-in recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Employee Daily Check-out
     */
    public function checkOut(Request $request)
    {
        try {
            $data = $request->only(['latitude', 'longitude']);
            $data['ip'] = $request->ip();
            $user = Auth::user();

            $this->attendanceService->handleCheckOut($user, $data);
            return back()->with('success', 'Check-out updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Personal Attendance History
     */
    public function logs()
    {
        $logs = $this->attendanceService->getUserLogs(Auth::id(), 10);
        return view('backend.pages.attendances.logs', compact('logs'));
    }
}
