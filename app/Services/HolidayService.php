<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class HolidayService
{
    /**
     * Get Paginated Holidays with Search
     */
    public function getAllHolidays($perPage = 15, $search = null)
    {
        $query = Holiday::with('company');

        // If not super admin, filter by company
        if (Auth::user()->company_id) {
            $query->where('company_id', Auth::user()->company_id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('date', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('date', 'desc')->paginate($perPage)->withQueryString();
    }

    /**
     * Get Companies for Holiday Forms
     */
    public function getFormData()
    {
        return [
            'companies' => Company::where('status', 1)->get(),
        ];
    }

    public function createHoliday(array $data)
    {
        // If not super admin, ensure company_id is their own
        if (Auth::user()->company_id) {
            $data['company_id'] = Auth::user()->company_id;
        }

        return Holiday::create($data);
    }

    public function updateHoliday(Holiday $holiday, array $data)
    {
        // Permission check is usually done in controller, but we ensure company_id if needed
        if (Auth::user()->company_id) {
            $data['company_id'] = Auth::user()->company_id;
        }

        $holiday->update($data);
        return $holiday;
    }

    public function deleteHoliday(Holiday $holiday)
    {
        return $holiday->delete();
    }
}
