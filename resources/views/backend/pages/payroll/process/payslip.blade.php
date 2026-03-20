@extends('backend.layouts.app')

@section('title', 'Payslip: ' . $slip->payslip_no)

@section('content')
<div class="row d-flex justify-content-center">
    <div class="col-md-9 grid-margin stretch-card">
        <div class="card border-0 shadow-lg">
            <div class="card-body p-5">
                <!-- Watermark / Background subtle texture could be added here -->
                
                <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
                    <div>
                        <h2 class="text-primary font-weight-bold mb-1">{{ Auth::user()->company->name ?? 'Purple HR' }}</h2>
                        <p class="text-muted mb-0">{{ Auth::user()->company->address ?? 'Corporate Headquarters' }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark font-weight-bold mb-0">PAYSLIP</h3>
                        <p class="mb-0 text-muted">No: <strong>{{ $slip->payslip_no }}</strong></p>
                        <p class="mb-0 text-muted">Period: {{ date('F Y', mktime(0, 0, 0, $slip->batch->month, 1)) }}</p>
                    </div>
                </div>

                <div class="row mb-5 bg-light p-4 rounded border">
                    <div class="col-md-6 border-right">
                        <h5 class="text-muted border-bottom pb-2">Employee Details</h5>
                        <p class="mb-1">Name: <strong>{{ $slip->user->name }}</strong></p>
                        <p class="mb-1">ID: <strong>{{ $slip->user->employee_id ?? 'N/A' }}</strong></p>
                        <p class="mb-1">Designation: <strong>{{ $slip->user->designation->name ?? 'N/A' }}</strong></p>
                        <p class="mb-1">Department: <strong>{{ $slip->user->department->name ?? 'N/A' }}</strong></p>
                    </div>
                    <div class="col-md-6 pl-md-5">
                        <h5 class="text-muted border-bottom pb-2">Payment Details</h5>
                        <p class="mb-1">Bank Name: <strong>{{ $slip->user->bank_name ?? 'N/A' }}</strong></p>
                        <p class="mb-1">Account No: <strong>{{ $slip->user->account_no ?? 'N/A' }}</strong></p>
                        <p class="mb-1">Joining Date: <strong>{{ $slip->user->joining_date ?? 'N/A' }}</strong></p>
                        <p class="mb-1">Payment Method: <strong>{{ str_replace('_', ' ', strtoupper($slip->payment_method)) }}</strong></p>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6 pr-md-4">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Earnings</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slip->earnings_snapshot as $earning)
                                <tr>
                                    <td>{{ $earning['name'] }}</td>
                                    <td class="text-right">{{ number_format($earning['value'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="font-weight-bold bg-light">
                                <tr>
                                    <td>Total Earnings</td>
                                    <td class="text-right text-success">{{ number_format($slip->total_earnings, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-6 pl-md-4">
                        <table class="table table-bordered">
                            <thead class="bg-danger text-white">
                                <tr>
                                    <th>Deductions</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slip->deductions_snapshot as $deduction)
                                <tr>
                                    <td>{{ $deduction['name'] }}</td>
                                    <td class="text-right text-danger">{{ number_format($deduction['value'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="font-weight-bold bg-light">
                                <tr>
                                    <td>Total Deductions</td>
                                    <td class="text-right text-danger">{{ number_format($slip->total_deductions, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row justify-content-end mb-5">
                    <div class="col-md-5">
                        <div class="p-4 bg-dark text-white rounded shadow text-center">
                            <h5 class="mb-1 italic">Net Payable Amount</h5>
                            <h2 class="font-weight-bold mb-0 italic" style="font-size: 2.2rem;">{{ number_format($slip->net_payable, 2) }}</h2>
                            <small class="text-muted">(All amounts in BDT)</small>
                        </div>
                    </div>
                </div>

                <div class="row pt-5 mt-5">
                    <div class="col-md-6 border-top pt-3 text-center">
                        <p class="mb-0 italic text-muted">Employer Signature (Digitally Signed)</p>
                    </div>
                    <div class="col-md-6 border-top pt-3 text-center">
                        <p class="mb-0 italic text-muted">Employee Signature</p>
                    </div>
                </div>

                <div class="mt-5 text-center text-muted no-print">
                    <hr>
                    <button type="button" onclick="window.print()" class="btn btn-gradient-info mr-2">
                        <i class="mdi mdi-printer"></i> Print Payslip
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-light">Go Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.italic { font-style: italic; }
.card { border-radius: 15px; }

@media print {
    .page-header, .navbar, .sidebar, .footer, .no-print, nav, header { display: none !important; }
    .content-wrapper { padding: 0 !important; background: white !important; }
    body { background: white !important; padding: 0 !important; }
    .main-panel { width: 100% !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: none !important; }
    .row { display: flex !important; }
    .col-md-6 { width: 50% !important; }
    .table { width: 100% !important; }
}
</style>
@endsection
