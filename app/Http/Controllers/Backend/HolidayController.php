<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Holiday;

class HolidayController extends Controller
{
    protected $holidayService;

    public function __construct(\App\Services\HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = $this->holidayService->getAllHolidays(15, request('search'));
        return view('backend.pages.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formData = $this->holidayService->getFormData();
        return view('backend.pages.holidays.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'is_recurring' => $request->has('is_recurring') ? 1 : 0,
            'status' => $request->has('status') ? $request->status : 1
        ]);

        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'required|boolean',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $this->holidayService->createHoliday($data);
        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented for holidays
        return redirect()->route('holidays.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        $formData = $this->holidayService->getFormData();
        return view('backend.pages.holidays.edit', array_merge(['holiday' => $holiday], $formData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->merge([
            'is_recurring' => $request->has('is_recurring') ? 1 : 0,
            'status' => $request->has('status') ? $request->status : 1
        ]);

        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'required|boolean',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $this->holidayService->updateHoliday($holiday, $data);
        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        try {
            $this->holidayService->deleteHoliday($holiday);
            return back()->with('success', 'Holiday deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
