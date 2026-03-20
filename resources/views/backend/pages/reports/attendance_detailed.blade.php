@extends('backend.layouts.app')

@section('title', 'Detailed Attendance Report - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-clipboard-text"></i>
        </span> 
        Detailed Attendance Report
    </h3>
</div>

<!-- Filter Section -->
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('reports.attendance.detailed') }}" method="GET" class="forms-sample">
                    <div class="row items-center">
                        <div class="col-md-2">
                            <div class="form-group"><label>Start Date</label><input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-control"></div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group"><label>End Date</label><input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-control"></div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">All Branches</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $filters['branch_id'] == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
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
                                        <option value="{{ $user->id }}" {{ $filters['user_id'] == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-gradient-primary btn-block">Filter</button>
                            <button type="button" onclick="window.print()" class="btn btn-light btn-block"><i class="mdi mdi-printer"></i> Print</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($report as $row)
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-primary"><i class="mdi mdi-account"></i> {{ $row['user']->name }} ({{ $row['user']->employee_id }})</h5>
                    <span class="badge badge-outline-info">{{ $row['user']->department->name ?? 'No Dept' }}</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Late</th>
                                <th>Early Left</th>
                                <th>Duration</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $period = \Carbon\CarbonPeriod::create($filters['start_date'], $filters['end_date']);
                            @endphp
                            @foreach($period as $date)
                                @php
                                    $d = $date->format('Y-m-d');
                                    $rec = $row['details']->where('date', $d)->first();
                                    
                                    $status = 'ABSENT';
                                    $badge = 'badge-danger';
                                    
                                    if($rec) {
                                        if($rec->status == 1) { $status = 'PRESENT'; $badge = 'badge-success'; }
                                        elseif($rec->status == 2) { $status = 'LATE'; $badge = 'badge-warning'; }
                                        elseif($rec->status == 3) { $status = 'HALF DAY'; $badge = 'badge-info'; }
                                        elseif($rec->status == 4) { $status = 'LEAVE'; $badge = 'badge-primary'; }
                                        elseif($rec->status == 5) { $status = 'HOLIDAY'; $badge = 'badge-dark'; }
                                        elseif($rec->status == 6) { $status = 'WEEKLY OFF'; $badge = 'badge-secondary'; }
                                    }
                                @endphp
                                <tr class="{{ $date->isWeekend() ? 'bg-light' : '' }}">
                                    <td>{{ $date->format('d M (D)') }}</td>
                                    <td><label class="badge {{ $badge }}">{{ $status }}</label></td>
                                    <td>{{ $rec ? \App\Services\HelperService::formatTime($rec->check_in_time) : '-' }}</td>
                                    <td>{{ $rec ? \App\Services\HelperService::formatTime($rec->check_out_time) : '-' }}</td>
                                    <td class="text-danger">{{ ($rec && $rec->late_minutes > 0) ? $rec->late_minutes.'m' : '-' }}</td>
                                    <td class="text-warning">{{ ($rec && $rec->early_leaving_minutes > 0) ? $rec->early_leaving_minutes.'m' : '-' }}</td>
                                    <td>{{ ($rec && $rec->stay_minutes > 0) ? floor($rec->stay_minutes/60).'h '.($rec->stay_minutes%60).'m' : '-' }}</td>
                                    <td><small class="text-muted">{{ $rec ? ($rec->check_in_ip ?? '-') : '-' }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
@media print {
    .page-header, .forms-sample, .navbar, .sidebar, .btn { display: none !important; }
    .card { border: none !important; box-shadow: none !important; margin-bottom: 2rem !important; }
    .content-wrapper { padding: 0 !important; }
    body { background: white !important; }
    .main-panel { width: 100% !important; }
    .table-responsive { overflow: visible !important; }
}
</style>
@endsection
