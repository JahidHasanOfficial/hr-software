<?php

namespace App\Services;

use App\Models\Designation;
use App\Models\Department;

class DesignationService
{
    /**
     * Get Paginated Designations
     */
    public function getAllDesignations($perPage = 10)
    {
        return Designation::with(['department.branch.company'])->latest()->paginate($perPage);
    }

    /**
     * Get Data for Designation Forms
     */
    public function getFormData()
    {
        return [
            'departments' => Department::with('branch.company')->get(),
        ];
    }

    public function createDesignation(array $data)
    {
        return Designation::create($data);
    }

    public function updateDesignation(Designation $designation, array $data)
    {
        $designation->update($data);
        return $designation;
    }

    public function deleteDesignation(Designation $designation)
    {
        return $designation->delete();
    }
}
