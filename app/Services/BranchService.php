<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Shift;

class BranchService
{
    /**
     * Get Paginated Branches
     */
    public function getAllBranches($perPage = 10, $search = null)
    {
        $query = Branch::with(['company', 'shift']);
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }
        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Get Data for Branch Forms
     */
    public function getFormData()
    {
        return [
            'companies' => Company::where('status', 1)->get(),
            'shifts' => Shift::where('status', 1)->get(),
        ];
    }

    public function createBranch(array $data)
    {
        return Branch::create($data);
    }

    public function updateBranch(Branch $branch, array $data)
    {
        $branch->update($data);
        return $branch;
    }

    public function deleteBranch(Branch $branch)
    {
        return $branch->delete();
    }
}
