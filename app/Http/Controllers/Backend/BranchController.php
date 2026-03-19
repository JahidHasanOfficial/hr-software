<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreBranchRequest;
use App\Http\Requests\Backend\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\Company;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index()
    {
        $branches = Branch::with('company')->latest()->paginate(10);
        return view('backend.pages.branches.index', compact('branches'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('backend.pages.branches.create', compact('companies'));
    }

    public function store(StoreBranchRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->branchService->createBranch($request->validated());
            DB::commit();
            return redirect()->route('branches.index')->with('success', 'Branch created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(Branch $branch)
    {
        $companies = Company::all();
        return view('backend.pages.branches.edit', compact('branch', 'companies'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        DB::beginTransaction();
        try {
            $this->branchService->updateBranch($branch, $request->validated());
            DB::commit();
            return redirect()->route('branches.index')->with('success', 'Branch updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Branch $branch)
    {
        try {
            $this->branchService->deleteBranch($branch);
            return redirect()->route('branches.index')->with('success', 'Branch deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete branch.');
        }
    }
}
