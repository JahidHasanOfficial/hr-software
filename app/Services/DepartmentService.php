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
    public function getAllDepartments($perPage = 10, $search = null)
    {
        $query = Department::with(['branch.company', 'shift']);
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
        return $query->latest()->paginate($perPage)->withQueryString();
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
