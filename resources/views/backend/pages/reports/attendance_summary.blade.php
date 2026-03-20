@extends('backend.layouts.app')

@section('title', 'Attendance Summary Report - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-file-chart"></i>
        </span> 
        Attendance Summary Report
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Filter Reports</h4>
                <form action="{{ route('reports.attendance.summary') }}" method="GET" class="forms-sample">
                    <div class="row items-center">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">All Branches</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $filters['branch_id'] == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $filters['department_id'] == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Employee</label>
                                <select name="user_id" class="form-control">
                                    <option value="">All Employees</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $filters['user_id'] == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-gradient-primary btn-block">Filter Report</button>
                            <button type="button" onclick="window.print()" class="btn btn-light btn-block">
                                <i class="mdi mdi-printer"></i> Print
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Attendance Metrics: {{ date('d M Y', strtotime($filters['start_date'])) }} - {{ date('d M Y', strtotime($filters['end_date'])) }}</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-left py-3"> Employee </th>
                                <th> Total Days </th>
                                <th> Present </th>
                                <th> Late </th>
                                <th> On Leave </th>
                                <th> Absent </th>
                                <th> Performance </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report as $row)
                            @php
                                $perf = $row['summary']['total'] > 0 ? ($row['summary']['present'] / $row['summary']['total']) * 100 : 0;
                                $perfColor = $perf >= 90 ? 'text-success' : ($perf >= 70 ? 'text-warning' : 'text-danger');
                            @endphp
                            <tr>
                                <td class="text-left py-3">
                                    <div class="d-flex align-items-center">
                                        @if($row['user']->image)
                                            <img src="{{ asset('storage/' . $row['user']->image) }}" class="mr-3 rounded-circle" alt="image" style="width: 35px; height: 35px;">
                                        @endif
                                        <div>
                                            <p class="mb-0 font-weight-bold">{{ $row['user']->name }}</p>
                                            <small class="text-muted">{{ $row['user']->department->name ?? 'N/A' }} | {{ $row['user']->branch->name ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-weight-bold text-dark">{{ $row['summary']['total'] }}</td>
                                <td class="text-success font-weight-bold">{{ $row['summary']['present'] }}</td>
                                <td class="text-warning font-weight-bold">{{ $row['summary']['late'] }}</td>
                                <td class="text-info font-weight-bold">{{ $row['summary']['leave'] }}</td>
                                <td class="text-danger font-weight-bold">{{ $row['summary']['absent'] }}</td>
                                <td>
                                    <div class="progress progress-md" style="height: 10px;">
                                        <div class="progress-bar bg-{{ $perf >= 90 ? 'success' : ($perf >= 70 ? 'warning' : 'danger') }}" role="progressbar" style="width: {{ $perf }}%" aria-valuenow="{{ $perf }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="{{ $perfColor }} font-weight-bold">{{ round($perf, 1) }}%</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-5 text-center text-muted">No attendance data found for the selected period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .page-header, .forms-sample, .navbar, .sidebar, .btn {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .content-wrapper {
        padding: 0 !important;
    }
    body {
        background: white !important;
    }
    .main-panel {
        width: 100% !important;
    }
}
</style>
@endsection
