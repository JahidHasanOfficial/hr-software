<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollBatch;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        $this->authorize('payroll management');
        $batches = PayrollBatch::with('processedBy')->latest()->get();
        return view('backend.pages.payroll.process.index', compact('batches'));
    }

    public function preview(Request $request)
    {
        $this->authorize('payroll management');
        
        $request->validate([
            'month' => 'required',
            'year' => 'required'
        ]);

        $month = $request->month;
        $year = $request->year;

        // Check if already processed
        $existing = PayrollBatch::where('month', $month)->where('year', $year)->first();
        if ($existing && $existing->status == 'paid') {
            return back()->with('error', 'Payroll for this month is already paid and locked.');
        }

        $previewData = $this->payrollService->getPayrollPreview($month, $year);

        return view('backend.pages.payroll.process.preview', compact('previewData', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $this->authorize('payroll management');
        
        $this->payrollService->processBatch($request->month, $request->year, auth()->id());

        return redirect()->route('payroll.index')->with('success', 'Payroll processed successfully. Slips are now ready.');
    }

    public function show($id)
    {
        $this->authorize('payroll management');
        $batch = PayrollBatch::with('payslips.user.designation')->findOrFail($id);
        return view('backend.pages.payroll.process.show', compact('batch'));
    }

    public function payslip($id)
    {
        $this->authorize('payroll management');
        $slip = Payslip::with(['batch', 'user.designation', 'user.department'])->findOrFail($id);
        return view('backend.pages.payroll.process.payslip', compact('slip'));
    }
}
