<?php

namespace App\Http\Controllers\Backend\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Interview;
use App\Models\Candidate;
use App\Models\User;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interviews = Interview::with(['candidate', 'interviewers'])->latest()->paginate(10);
        return view('backend.pages.recruitment.interviews.index', compact('interviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $candidate = null;
        if ($request->has('candidate_id')) {
            $candidate = Candidate::findOrFail($request->candidate_id);
        }

        $candidates = Candidate::where('status', 'active')->get();
        // Assume users with certain roles or permissions are interviewers
        $interviewers = User::all(); // Simplified for now

        return view('backend.pages.recruitment.interviews.create', compact('candidate', 'candidates', 'interviewers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string',
            'interviewers' => 'required|array',
            'interview_type' => 'required|string'
        ]);

        $interview = Interview::create([
            'candidate_id' => $request->candidate_id,
            'title' => $request->title,
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
            'interview_type' => $request->interview_type,
            'status' => 'scheduled'
        ]);

        $interview->interviewers()->sync($request->interviewers);

        return redirect()->route('recruitment.interviews.index')
            ->with('success', 'Interview scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $interview = Interview::with(['candidate', 'interviewers', 'scorecards'])->findOrFail($id);
        return view('backend.pages.recruitment.interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $interview = Interview::findOrFail($id);
        $candidates = Candidate::all();
        $interviewers = User::all();
        return view('backend.pages.recruitment.interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $interview = Interview::findOrFail($id);

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'interviewers' => 'required|array',
        ]);

        $interview->update($request->except('interviewers'));
        $interview->interviewers()->sync($request->interviewers);

        return redirect()->route('recruitment.interviews.index')
            ->with('success', 'Interview updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $interview = Interview::findOrFail($id);
        $interview->interviewers()->detach();
        $interview->delete();

        return redirect()->route('recruitment.interviews.index')
            ->with('success', 'Interview deleted successfully.');
    }
}
