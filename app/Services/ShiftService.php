<?php

namespace App\Services;

use App\Models\Shift;

class ShiftService
{
    /**
     * Get Paginated Shifts
     */
    public function getAllShifts($perPage = 10)
    {
        return Shift::latest()->paginate($perPage);
    }

    /**
     * Get All Active Shifts
     */
    public function getActiveShifts()
    {
        return Shift::where('status', 1)->get();
    }

    public function createShift(array $data)
    {
        return Shift::create($data);
    }

    public function updateShift(Shift $shift, array $data)
    {
        $shift->update($data);
        return $shift;
    }

    public function deleteShift(Shift $shift)
    {
        return $shift->delete();
    }
}
