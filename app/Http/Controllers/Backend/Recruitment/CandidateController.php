<?php

namespace App\Http\Controllers\Backend\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Candidate;
use App\Models\CandidateStage;
use App\Models\JobPost;
use App\Services\RecruitmentService;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    protected $recruitmentService;

    public function __construct(RecruitmentService $recruitmentService)
    {
        $this->recruitmentService = $recruitmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Candidate::with(['jobPost', 'stage'])->latest();

        if ($request->has('job_post_id')) {
            $query->where('job_post_id', $request->job_post_id);
        }

        $candidates = $query->paginate(10);
        $jobPosts = JobPost::where('is_published', 1)->get();

        return view('backend.pages.recruitment.candidates.index', compact('candidates', 'jobPosts'));
    }

    /**
     * Show Kanban board.
     */
    public function kanban()
    {
        $stages = $this->recruitmentService->getKanbanData();
        return view('backend.pages.recruitment.candidates.kanban', compact('stages'));
    }

    /**
     * Move candidate (AJAX)
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'candidate_stage_id' => 'required|exists:candidate_stages,id'
        ]);

        $candidate = $this->recruitmentService->moveCandidate($id, $request->candidate_stage_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Candidate moved to ' . $candidate->stage->name
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobPosts = JobPost::where('is_published', 1)->get();
        $stages = CandidateStage::orderBy('order')->get();
        return view('backend.pages.recruitment.candidates.create', compact('jobPosts', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email',
            'job_post_id' => 'required|exists:job_posts,id',
            'candidate_stage_id' => 'required|exists:candidate_stages,id',
            'resume' => 'nullable|mimes:pdf,docx,doc|max:5120', // Max 5MB
        ]);

        // Duplicate Check
        if ($this->recruitmentService->isDuplicate($request->email, $request->job_post_id)) {
            return redirect()->back()->withInput()->with('error', 'Candidate already applied for this position.');
        }

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        Candidate::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'job_post_id' => $request->job_post_id,
            'candidate_stage_id' => $request->candidate_stage_id,
            'source' => $request->source,
            'resume_path' => $resumePath,
            'expected_salary' => $request->expected_salary,
            'experience_years' => $request->experience_years,
        ]);

        return redirect()->route('recruitment.candidates.index')
            ->with('success', 'Candidate added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidate = Candidate::with(['jobPost', 'stage', 'interviews', 'scorecards'])->findOrFail($id);
        return view('backend.pages.recruitment.candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $candidate = Candidate::findOrFail($id);
        $jobPosts = JobPost::where('is_published', 1)->get();
        $stages = CandidateStage::orderBy('order')->get();
        return view('backend.pages.recruitment.candidates.edit', compact('candidate', 'jobPosts', 'stages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $id,
            'job_post_id' => 'required|exists:job_posts,id',
            'candidate_stage_id' => 'required|exists:candidate_stages,id',
        ]);

        if ($request->hasFile('resume')) {
            if ($candidate->resume_path) {
                Storage::delete('public/' . $candidate->resume_path);
            }
            $candidate->resume_path = $request->file('resume')->store('resumes', 'public');
        }

        $candidate->update($request->except('resume'));

        return redirect()->route('recruitment.candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $candidate = Candidate::findOrFail($id);
        if ($candidate->resume_path) {
            Storage::delete('public/' . $candidate->resume_path);
        }
        $candidate->delete();

        return redirect()->route('recruitment.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }
}
