<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the leave types.
     */
    public function index()
    {
        $leaveTypes = LeaveType::latest()->paginate(15);
        return view('backend.pages.leave_types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new leave type.
     */
    public function create()
    {
        return view('backend.pages.leave_types.create');
    }

    /**
     * Store a newly created leave type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:leave_types,code|max:10',
            'quota' => 'required|numeric|min:0',
        ]);

        LeaveType::create($request->all());

        return redirect()->route('leave-types.index')->with('success', 'Leave type created successfully.');
    }

    /**
     * Show the form for editing the leave type.
     */
    public function edit(LeaveType $leaveType)
    {
        return view('backend.pages.leave_types.edit', compact('leaveType'));
    }

    /**
     * Update the leave type.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:leave_types,code,' . $leaveType->id,
            'quota' => 'required|numeric|min:0',
        ]);

        $leaveType->update($request->all());

        return redirect()->route('leave-types.index')->with('success', 'Leave type updated successfully.');
    }

    /**
     * Remove the leave type.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return redirect()->route('leave-types.index')->with('success', 'Leave type deleted successfully.');
    }
}
