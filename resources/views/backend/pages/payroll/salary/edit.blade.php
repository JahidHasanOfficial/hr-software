@extends('backend.layouts.app')

@section('title', 'Setup Salary: ' . $user->name)

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-buffer"></i>                 
        </span> 
        Detailed Salary Setup: {{ $user->name }}
    </h3>
    <a href="{{ route('employee-salary.index') }}" class="btn btn-light btn-sm"><i class="mdi mdi-arrow-left"></i> Back to List</a>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('employee-salary.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Gross Monthly <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="gross_monthly" id="gross_monthly" value="{{ $salary->gross_monthly ?? 0 }}" class="form-control font-weight-bold" style="font-size: 1.2rem;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Currency</label>
                                <select name="currency" class="form-control">
                                    <option value="BDT" {{ ($salary->currency ?? '') == 'BDT' ? 'selected' : '' }}>BDT</option>
                                    <option value="USD" {{ ($salary->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-success font-weight-bold">Current Net Calculation</label>
                                <h3 class="text-success" id="net_calculation">0.00</h3>
                                <input type="hidden" id="net_monthly_input" name="net_monthly">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 pr-md-4">
                            <h5 class="mb-4 text-primary border-bottom pb-2">Earnings (Allowances)</h5>
                            @foreach($components->where('type', 'earning') as $comp)
                            <div class="form-group row align-items-center">
                                <label class="col-sm-6">{{ $comp->name }} ({{ ucfirst($comp->unit) }})</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="components[{{ $comp->id }}]" 
                                            class="form-control earning-field" 
                                            data-unit="{{ $comp->unit }}"
                                            value="{{ $salaryDetails[$comp->id] ?? 0 }}">
                                        @if($comp->unit == 'percentage') <div class="input-group-append"><span class="input-group-text">%</span></div> @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="col-md-6 pl-md-4">
                            <h5 class="mb-4 text-danger border-bottom pb-2">Deductions</h5>
                            @foreach($components->whereIn('type', ['deduction', 'reimbursement']) as $comp)
                            <div class="form-group row align-items-center">
                                <label class="col-sm-6">{{ $comp->name }} ({{ ucfirst($comp->unit) }})</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="components[{{ $comp->id }}]" 
                                            class="form-control deduction-field bg-light-danger" 
                                            data-unit="{{ $comp->unit }}"
                                            value="{{ $salaryDetails[$comp->id] ?? 0 }}">
                                        @if($comp->unit == 'percentage') <div class="input-group-append"><span class="input-group-text">%</span></div> @endif
                                    </div>
                                    <small class="text-muted d-block mt-1 text-right component-calc-preview">Calculated: 0.00</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-5 border-top pt-4 text-right">
                        <button type="submit" class="btn btn-gradient-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Save Salary Setup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    function calculateNet() {
        let gross = parseFloat($('#gross_monthly').val()) || 0;
        let totalEarnings = 0;
        let totalDeductions = 0;

        $('.earning-field').each(function() {
            let val = parseFloat($(this).val()) || 0;
            let unit = $(this).data('unit');
            let actual = (unit === 'percentage') ? (gross * (val / 100)) : val;
            totalEarnings += actual;
            $(this).parent().parent().find('.component-calc-preview').text('Actual: ' + actual.toFixed(2));
        });

        $('.deduction-field').each(function() {
            let val = parseFloat($(this).val()) || 0;
            let unit = $(this).data('unit');
            let actual = (unit === 'percentage') ? (gross * (val / 100)) : val;
            totalDeductions += actual;
            $(this).parent().parent().find('.component-calc-preview').text('Deducting: ' + actual.toFixed(2));
        });

        let netFinal = totalEarnings - totalDeductions;
        $('#net_calculation').text(netFinal.toLocaleString(undefined, {minimumFractionDigits: 2}));
        $('#net_monthly_input').val(netFinal.toFixed(2));
    }

    $('#gross_monthly, .earning-field, .deduction-field').on('input', function() {
        calculateNet();
    });

    calculateNet(); // Initial calc
});
</script>
<style>
.bg-light-danger { background-color: #fff8f8; }
.input-group-text { padding: 0.4rem 0.6rem; }
</style>
@endpush
@endsection
