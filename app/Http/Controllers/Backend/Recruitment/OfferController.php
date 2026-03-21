<?php

namespace App\Http\Controllers\Backend\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Offer;
use App\Models\Candidate;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::with(['candidate'])->latest()->paginate(10);
        return view('backend.pages.recruitment.offers.index', compact('offers'));
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

        return view('backend.pages.recruitment.offers.create', compact('candidate', 'candidates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'offered_salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'terms_and_conditions' => 'nullable|string'
        ]);

        Offer::create([
            'candidate_id' => $request->candidate_id,
            'offered_salary' => $request->offered_salary,
            'joining_date' => $request->joining_date,
            'status' => 'sent',
            'terms_and_conditions' => $request->terms_and_conditions,
        ]);

        return redirect()->route('recruitment.offers.index')
            ->with('success', 'Offer generated and sent to candidate.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $offer = Offer::with(['candidate'])->findOrFail($id);
        return view('backend.pages.recruitment.offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $offer = Offer::findOrFail($id);
        $candidates = Candidate::all();
        return view('backend.pages.recruitment.offers.edit', compact('offer', 'candidates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $offer = Offer::findOrFail($id);

        $request->validate([
            'offered_salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'status' => 'required|in:sent,accepted,declined,expired'
        ]);

        $offer->update($request->all());

        return redirect()->route('recruitment.offers.index')
            ->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        return redirect()->route('recruitment.offers.index')
            ->with('success', 'Offer deleted successfully.');
    }
}
