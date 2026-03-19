<?php

namespace App\Services;

use App\Models\Department;

class DepartmentService
{
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
