<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\DepartmentService;
use App\Http\Requests\Backend\StoreDepartmentRequest;
use App\Http\Requests\Backend\UpdateDepartmentRequest;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        $departments = $this->departmentService->getAllDepartments(10);
        return view('backend.pages.departments.index', compact('departments'));
    }

    public function create()
    {
        $data = $this->departmentService->getFormData();
        return view('backend.pages.departments.create', $data);
    }

    public function store(StoreDepartmentRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->departmentService->createDepartment($request->validated());
            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(Department $department)
    {
        $data = $this->departmentService->getFormData();
        $data['department'] = $department;
        return view('backend.pages.departments.edit', $data);
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        DB::beginTransaction();
        try {
            $this->departmentService->updateDepartment($department, $request->validated());
            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Department $department)
    {
        try {
            $this->departmentService->deleteDepartment($department);
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete department.');
        }
    }
}
