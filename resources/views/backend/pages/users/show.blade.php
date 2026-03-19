@extends('backend.layouts.app')

@section('title', 'Employee Details - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-card-details"></i>                 
        </span>
        Employee Profile
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Employees</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                @if($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" alt="user image" style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid #b66dff;">
                @else
                    <img src="{{ asset('backend/images/faces/face1.jpg') }}" alt="user image" style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid #b66dff;">
                @endif
                <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->designation->name ?? 'Employee' }}</p>
                @foreach($user->roles as $role)
                    <label class="badge badge-gradient-primary">{{ $role->name }}</label>
                @endforeach
                <div class="mt-4 pt-3 border-top">
                    <p class="mb-2"><strong>Status:</strong> 
                        {!! \App\Services\HelperService::getStatusBadge($user->status) !!}
                    </p>
                    <p class="mb-0"><strong>Joined:</strong> {{ $user->joining_date ?? 'N/A' }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-gradient-warning btn-sm btn-block">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detailed Information</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Full Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $user->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Designation</th>
                                <td>{{ $user->designation->name ?? 'Not assigned' }}</td>
                            </tr>
                            <tr>
                                <th>Office Shift</th>
                                <td>
                                    @if($user->shift)
                                        <label class="badge badge-gradient-info">{{ $user->shift->name }}</label>
                                        <br>
                                        <small>{{ \App\Services\HelperService::formatTime($user->shift->start_time) }} - {{ \App\Services\HelperService::formatTime($user->shift->end_time) }}</small>
                                    @else
                                        <span class="text-danger small">No shift assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Joining Date</th>
                                <td>{{ $user->joining_date ? date('d M, Y', strtotime($user->joining_date)) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Monthly Salary</th>
                                <td>${{ number_format($user->salary, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
