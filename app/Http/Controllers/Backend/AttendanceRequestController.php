<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRequest;
use Illuminate\Http\Request;

class AttendanceRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = AttendanceRequest::with('user')->orderBy('created_at', 'desc');
        
        // Filter for employees
        if (!$user->hasRole('Admin') && !$user->hasRole('HR Manager') && !$user->hasPermissionTo('attendance management')) {
            $query->where('user_id', $user->id);
        }

        $requests = $query->paginate(15);
        return view('backend.pages.attendance_requests.index', compact('requests'));
    }
}
