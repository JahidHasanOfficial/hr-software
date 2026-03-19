<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreDesignationRequest;
use App\Http\Requests\Backend\UpdateDesignationRequest;
use App\Models\Designation;
use App\Models\Department;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    protected $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
    }

    public function index()
    {
        $designations = Designation::with('department.branch.company')->latest()->paginate(10);
        return view('backend.pages.designations.index', compact('designations'));
    }

    public function create()
    {
        $departments = Department::with('branch.company')->get();
        return view('backend.pages.designations.create', compact('departments'));
    }

    public function store(StoreDesignationRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->designationService->createDesignation($request->validated());
            DB::commit();
            return redirect()->route('designations.index')->with('success', 'Designation created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(Designation $designation)
    {
        $departments = Department::with('branch.company')->get();
        return view('backend.pages.designations.edit', compact('designation', 'departments'));
    }

    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        DB::beginTransaction();
        try {
            $this->designationService->updateDesignation($designation, $request->validated());
            DB::commit();
            return redirect()->route('designations.index')->with('success', 'Designation updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Designation $designation)
    {
        try {
            $this->designationService->deleteDesignation($designation);
            return redirect()->route('designations.index')->with('success', 'Designation deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete designation.');
        }
    }
}
