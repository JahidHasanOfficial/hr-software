<?php

namespace App\Services;

use App\Models\Branch;

class BranchService
{
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
