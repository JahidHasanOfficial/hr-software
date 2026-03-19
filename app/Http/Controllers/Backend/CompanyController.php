<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreCompanyRequest;
use App\Http\Requests\Backend\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $companies = Company::latest()->paginate(10);
        return view('backend.pages.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('backend.pages.companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->companyService->createCompany($request->validated());
            DB::commit();
            return redirect()->route('companies.index')->with('success', 'Company created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show(Company $company)
    {
        return view('backend.pages.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('backend.pages.companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $this->companyService->updateCompany($company, $request->validated());
            DB::commit();
            return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        try {
            $this->companyService->deleteCompany($company);
            return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not delete company.');
        }
    }
}
