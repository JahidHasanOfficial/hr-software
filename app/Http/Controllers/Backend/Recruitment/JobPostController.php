<?php

namespace App\Http\Controllers\Backend\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobPost;
use App\Models\JobRequisition;
use Illuminate\Support\Str;

class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = JobPost::with('jobRequisition')->latest()->paginate(10);
        return view('backend.pages.recruitment.job_posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $requisition = null;
        if ($request->has('requisition_id')) {
            $requisition = JobRequisition::findOrFail($request->requisition_id);
        }

        return view('backend.pages.recruitment.job_posts.create', compact('requisition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employment_type' => 'required|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'expiry_date' => 'nullable|date',
            'job_requisition_id' => 'nullable|exists:job_requisitions,id',
        ]);

        // Auto-generate Job Code: e.g. JOB-2024-XXXX
        $jobCode = 'JOB-' . date('Y') . '-' . strtoupper(Str::random(5));

        JobPost::create([
            'job_requisition_id' => $request->job_requisition_id,
            'job_code' => $jobCode,
            'title' => $request->title,
            'description' => $request->description,
            'employment_type' => $request->employment_type,
            'location' => $request->location,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'expiry_date' => $request->expiry_date,
            'is_published' => $request->has('publish'),
        ]);

        return redirect()->route('recruitment.job-posts.index')
            ->with('success', 'Job post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = JobPost::with('jobRequisition')->findOrFail($id);
        return view('backend.pages.recruitment.job_posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = JobPost::findOrFail($id);
        return view('backend.pages.recruitment.job_posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = JobPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'employment_type' => 'required|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'expiry_date' => 'nullable|date',
        ]);

        $post->update($request->except('publish'));

        if ($request->has('publish')) {
            $post->update(['is_published' => true]);
        }

        return redirect()->route('recruitment.job-posts.index')
            ->with('success', 'Job post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = JobPost::findOrFail($id);
        $post->delete();

        return redirect()->route('recruitment.job-posts.index')
            ->with('success', 'Job post deleted successfully.');
    }

    public function publish($id)
    {
        $post = JobPost::findOrFail($id);
        $post->update(['is_published' => true]);

        return redirect()->back()->with('success', 'Job post published successfully.');
    }
}
