@extends('backend.layouts.app')

@section('title', 'Payroll Preview - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-eye-check"></i>                 
        </span> 
        Payroll Preview: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
    </h3>
    <a href="{{ route('payroll.index') }}" class="btn btn-light btn-sm"><i class="mdi mdi-arrow-left"></i> Change Cycle</a>
</div>

<div class="row">
    <div class="col-lg-12 shadow-sm grid-margin stretch-card">
        <div class="card border-0">
            <div class="card-body">
                <form action="{{ route('payroll.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">

                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-hover">
                            <thead class="bg-gradient-primary text-white shadow-sm">
                                <tr>
                                    <th>Employee</th>
                                    <th>Gross (Setup)</th>
                                    <th>Total Earnings</th>
                                    <th>Total Deductions</th>
                                    <th class="bg-dark">Net Payable</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewData as $row)
                                <tr>
                                    <td class="text-left py-3">
                                        <h6 class="mb-0 font-weight-bold">{{ $row['employee']->name }}</h6>
                                        <small class="text-muted">{{ $row['employee']->designation->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="font-weight-bold">{{ number_format($row['employee']->salarySetup->gross_monthly, 2) }}</td>
                                    <td class="text-success font-weight-bold italic">+{{ number_format($row['calc']['total_earnings'], 2) }}</td>
                                    <td class="text-danger font-weight-bold italic">-{{ number_format($row['calc']['total_deductions'], 2) }}</td>
                                    <td class="bg-light-primary font-weight-bold h5 mb-0" style="font-size: 1.1rem;">
                                        {{ number_format($row['calc']['net_payable'], 2) }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-outline-info" data-toggle="collapse" data-target="#breakdown{{ $row['employee']->id }}">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <!-- Detailed Breakdown Row -->
                                <tr id="breakdown{{ $row['employee']->id }}" class="collapse bg-light">
                                    <td colspan="6" class="p-3 text-left">
                                        <div class="row">
                                            <div class="col-md-6 border-right pr-4">
                                                <h6 class="text-primary border-bottom pb-2">Earnings Breakdown</h6>
                                                @foreach($row['calc']['earnings'] as $slug => $earning)
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>{{ $earning['name'] }}</span>
                                                    <span class="font-weight-bold">{{ number_format($earning['value'], 2) }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6 pl-4">
                                                <h6 class="text-danger border-bottom pb-2">Deductions Breakdown</h6>
                                                @foreach($row['calc']['deductions'] as $slug => $deduction)
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>{{ $deduction['name'] }}</span>
                                                    <span class="font-weight-bold text-danger">{{ number_format($deduction['value'], 2) }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 text-right p-4 bg-light border-top rounded">
                        <div class="mb-3">
                            <h4 class="text-muted">Summary for Cycle</h4>
                            <h2 class="text-primary font-weight-bold" id="batch_total_net">
                                Total Batch Net: {{ number_format(collect($previewData)->sum('calc.net_payable'), 2) }}
                            </h2>
                        </div>
                        <button type="submit" class="btn btn-gradient-success btn-lg px-5 shadow-sm" onclick="return confirm('Process and generate all payslips now?')">
                            <i class="mdi mdi-check-all"></i> Finalize & Generate Payslips
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-primary { background-color: #f8fbff; }
.italic { font-style: italic; }
.table-hover tbody tr:hover td { background-color: rgba(0,0,0,0.02); }
</style>
@endsection
