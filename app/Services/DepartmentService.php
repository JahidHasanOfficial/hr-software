<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Branch;
use App\Models\Shift;

class DepartmentService
{
    /**
     * Get Paginated Departments
     */
    public function getAllDepartments($perPage = 10)
    {
        return Department::with(['branch.company', 'shift'])->latest()->paginate($perPage);
    }

    /**
     * Get Data for Department Forms
     */
    public function getFormData()
    {
        return [
            'branches' => Branch::with('company')->get(),
            'shifts' => Shift::where('status', 1)->get(),
        ];
    }

    public function createDepartment(array $data)
    {
        return Department::create($data);
    }

    public function updateDepartment(Department $department, array $data)
    {
        $department->update($data);
        return $department;
    }

    public function deleteDepartment(Department $department)
    {
        return $department->delete();
    }
}
