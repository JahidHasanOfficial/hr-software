<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Services\ShiftService;
use App\Http\Requests\Backend\StoreShiftRequest;
use App\Http\Requests\Backend\UpdateShiftRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function index()
    {
        $shifts = $this->shiftService->getAllShifts(10);
        return view('backend.pages.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('backend.pages.shifts.create');
    }

    public function store(StoreShiftRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->shiftService->createShift($request->validated());
            DB::commit();
            return redirect()->route('shifts.index')->with('success', 'Shift created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(Shift $shift)
    {
        return view('backend.pages.shifts.edit', compact('shift'));
    }

    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        DB::beginTransaction();
        try {
            $this->shiftService->updateShift($shift, $request->validated());
            DB::commit();
            return redirect()->route('shifts.index')->with('success', 'Shift updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Shift $shift)
    {
        try {
            $this->shiftService->deleteShift($shift);
            return redirect()->route('shifts.index')->with('success', 'Shift deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete shift.');
        }
    }
}
