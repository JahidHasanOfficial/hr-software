<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SalaryComponentController extends Controller
{
    public function index()
    {
        $this->authorize('payroll management');
        $components = SalaryComponent::latest()->get();
        return view('backend.pages.payroll.components.index', compact('components'));
    }

    public function store(Request $request)
    {
        $this->authorize('payroll management');
        
        $request->validate([
            'name' => 'required|string|max:100|unique:salary_components',
            'type' => 'required|in:earning,deduction,reimbursement',
            'unit' => 'required|in:fixed,percentage',
            'is_taxable' => 'boolean',
            'is_statutory' => 'boolean',
        ]);

        SalaryComponent::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'unit' => $request->unit,
            'is_taxable' => $request->is_taxable ?? 0,
            'is_statutory' => $request->is_statutory ?? 0,
        ]);

        return back()->with('success', 'Salary component created successfully.');
    }

    public function update(Request $request, SalaryComponent $component)
    {
        $this->authorize('payroll management');
        
        $request->validate([
            'name' => 'required|string|max:100|unique:salary_components,name,' . $component->id,
            'type' => 'required|in:earning,deduction,reimbursement',
            'unit' => 'required|in:fixed,percentage',
        ]);

        $component->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'unit' => $request->unit,
            'is_taxable' => $request->is_taxable ?? 0,
            'is_statutory' => $request->is_statutory ?? 0,
            'status' => $request->status ?? 1
        ]);

        return back()->with('success', 'Salary component updated.');
    }

    public function destroy(SalaryComponent $component)
    {
        $this->authorize('payroll management');
        $component->delete();
        return back()->with('success', 'Component deleted.');
    }
}
