@extends('backend.layouts.app')

@section('title', 'Weekly Off Settings')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-clock-alert"></i>                 
        </span>
        Weekly Off Schedules
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Weekly Off</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Weekly Off Schedules</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        @include('backend.components.search_box', ['action' => route('weekly-offs.index'), 'placeholder' => 'Search by day...'])
                        <a href="#" class="btn btn-gradient-primary btn-sm btn-fw ml-2">
                            <i class="mdi mdi-plus"></i> Configure Day
                        </a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Day Name</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = [0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
                            @endphp
                            @foreach($weeklyOffs as $off)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $days[$off->day] ?? 'Unknown' }}</td>
                                <td>{{ $off->branch->name ?? 'ALL BRANCHES' }}</td>
                                <td>{{ $off->department->name ?? 'ALL DEPARTMENTS' }}</td>
                                <td>{!! \App\Services\HelperService::getStatusBadge(1) !!}</td>
                                <td>
                                    <button class="btn btn-outline-danger btn-xs"><i class="mdi mdi-trash-can"></i></button>
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
@endsection
