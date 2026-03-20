<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Response;

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
     * Correct Attendance Manually (Admin Only)
     */
    public function storeManualCorrection(Request $request)
    {
        $this->authorize('attendance.correction');
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in_time' => 'required',
            'status' => 'required|in:1,2,3,4,5,6',
            'reason' => 'required|string|min:5'
        ]);

        $admin = Auth::user();
        $employee = User::findOrFail($request->user_id);

        try {
            $this->attendanceService->handleManualCorrection($admin, $employee, $request->all());
            return back()->with('success', 'Attendance corrected manually.');
        } catch (\Exception $e) {
            return back()->with('error', 'Update failed: '.$e->getMessage());
        }
    }

    /**
     * Download Attendance as CSV
     */
    public function exportCsv()
    {
        $this->authorize('attendance.export');
        
        $attendances = Attendance::with('user')->orderBy('date', 'desc')->get();
        $filename = "attendance_report_".date('Ymd').".csv";
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Employee', 'Date', 'In Time', 'Out Time', 'Status', 'Duration (m)', 'Late (m)');

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attendances as $att) {
                $statusMap = [1 => 'Present', 2 => 'Late', 3 => 'Half Day', 4 => 'Leave', 5 => 'Holiday', 6 => 'Weekly Off'];
                fputcsv($file, array(
                    $att->user->name,
                    $att->date,
                    $att->check_in_time,
                    $att->check_out_time,
                    $statusMap[$att->status] ?? 'N/A',
                    $att->stay_minutes,
                    $att->late_minutes
                ));
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
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
