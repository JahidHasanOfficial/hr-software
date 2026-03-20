@extends('backend.layouts.app')

@section('title', 'Payroll Batch Details - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-clipboard-text"></i>                 
        </span> 
        Payroll Cycle Slips: {{ date('F', mktime(0, 0, 0, $batch->month, 1)) }} {{ $batch->year }}
    </h3>
    <a href="{{ route('payroll.index') }}" class="btn btn-light btn-sm"><i class="mdi mdi-arrow-left"></i> Back to Batches</a>
</div>

<div class="row">
    <div class="col-lg-12 shadow-sm grid-margin stretch-card">
        <div class="card border-0">
            <div class="card-body">
                <div class="row mb-5 text-center p-4 bg-light rounded mx-2 shadow-sm border border-primary">
                    <div class="col-md-3">
                        <small class="text-muted d-block mb-1">Total Gross</small>
                        <h4 class="text-primary font-weight-bold mb-0 italic">{{ number_format($batch->total_gross, 2) }}</h4>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block mb-1">Total Deductions</small>
                        <h4 class="text-danger font-weight-bold mb-0 italic">{{ number_format($batch->total_deductions, 2) }}</h4>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block mb-1">Total Net Payable</small>
                        <h4 class="text-success font-weight-bold mb-0 italic" style="font-size: 1.4rem;">{{ number_format($batch->total_net, 2) }}</h4>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge badge-gradient-success mt-1">{{ strtoupper($batch->status) }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-center table-hover shadow-sm rounded overflow-hidden">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th>Payslip No</th>
                                <th>Employee</th>
                                <th>Designation</th>
                                <th>Net Payable</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->payslips as $slip)
                            <tr>
                                <td class="font-weight-bold">{{ $slip->payslip_no }}</td>
                                <td class="text-left font-weight-bold">{{ $slip->user->name }}</td>
                                <td>{{ $slip->user->designation->name ?? 'N/A' }}</td>
                                <td class="font-weight-bold text-success" style="font-size: 1.1rem;">{{ number_format($slip->net_payable, 2) }}</td>
                                <td>
                                    <label class="badge badge-outline-{{ $slip->status == 'unpaid' ? 'warning' : 'success' }}">
                                        {{ ucfirst($slip->status) }}
                                    </label>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('payroll.payslip', $slip->id) }}" class="btn btn-sm btn-gradient-info rounded-pill px-3 shadow-sm" title="View/Download Payslip">
                                        <i class="mdi mdi-file-pdf"></i> View Payslip
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-dark { background: linear-gradient(to right, #2c3e50, #000000); }
.italic { font-style: italic; }
.card { overflow: visible !important; }
</style>
@endsection
