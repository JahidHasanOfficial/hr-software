<?php

namespace App\Http\Controllers\Backend\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobRequisition;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;

class JobRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requisitions = JobRequisition::with(['department', 'designation', 'requester'])
            ->latest()
            ->paginate(10);

        return view('backend.pages.recruitment.job_requisitions.index', compact('requisitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('status', 1)->get();
        $designations = Designation::where('status', 1)->get();

        return view('backend.pages.recruitment.job_requisitions.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'headcount' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'justification' => 'nullable|string',
            'budget_details' => 'nullable|string',
        ]);

        JobRequisition::create([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'headcount' => $request->headcount,
            'urgency_level' => $request->urgency_level,
            'justification' => $request->justification,
            'budget_details' => $request->budget_details,
            'status' => 'pending',
            'requested_by' => Auth::id(),
        ]);

        return redirect()->route('recruitment.job-requisitions.index')
            ->with('success', 'Job requisition created successfully and sent for approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $requisition = JobRequisition::with(['department', 'designation', 'requester'])->findOrFail($id);
        return view('backend.pages.recruitment.job_requisitions.show', compact('requisition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $requisition = JobRequisition::findOrFail($id);
        $departments = Department::where('status', 1)->get();
        $designations = Designation::where('status', 1)->get();

        return view('backend.pages.recruitment.job_requisitions.edit', compact('requisition', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requisition = JobRequisition::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'headcount' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'justification' => 'nullable|string',
            'budget_details' => 'nullable|string',
        ]);

        $requisition->update($request->all());

        return redirect()->route('recruitment.job-requisitions.index')
            ->with('success', 'Job requisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $requisition = JobRequisition::findOrFail($id);
        $requisition->delete();

        return redirect()->route('recruitment.job-requisitions.index')
            ->with('success', 'Job requisition deleted successfully.');
    }

    public function approve($id)
    {
        $requisition = JobRequisition::findOrFail($id);
        $requisition->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Job requisition approved.');
    }

    public function reject($id)
    {
        $requisition = JobRequisition::findOrFail($id);
        $requisition->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Job requisition rejected.');
    }
}
