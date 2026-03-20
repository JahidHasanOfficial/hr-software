<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRequest;
use Illuminate\Http\Request;

class AttendanceRequestController extends Controller
{
    public function index()
    {
        $requests = AttendanceRequest::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.pages.attendance_requests.index', compact('requests'));
    }
}
