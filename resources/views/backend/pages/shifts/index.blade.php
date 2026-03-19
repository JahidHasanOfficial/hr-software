@extends('backend.layouts.app')

@section('title', 'Manage Shifts - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-clock-outline"></i>                 
        </span>
        Shift Management
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shifts</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Employee Shifts</h4>
                    <a href="{{ route('shifts.create') }}" class="btn btn-gradient-primary btn-fw">Add Shift</a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Shift Name</th>
                                <th>Schedule</th>
                                <th>Late Threshold</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shifts as $shift)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $shift->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>
                                <td>{{ $shift->late_threshold }} mins</td>
                                <td>
                                    <span class="badge {{ $shift->is_flexible ? 'badge-gradient-info' : 'badge-gradient-dark' }}">
                                        {{ $shift->is_flexible ? 'Flexible' : 'Fixed' }}
                                    </span>
                                </td>
                                <td>
                                    <label class="badge {{ $shift->status == 'active' ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                        {{ strtoupper($shift->status) }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-sm btn-gradient-warning">Edit</a>
                                    <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger" onclick="return confirm('Delete shift?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
