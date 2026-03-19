<?php

namespace App\Services;

use App\Models\Designation;

class DesignationService
{
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
