@extends('backend.layouts.app')

@section('title', 'Dashboard - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-home"></i>                 
        </span>
        Dashboard
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview
                <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <!-- Attendance Quick Action -->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card bg-gradient-light border">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="mb-2">Hello, <span class="text-primary font-weight-bold">{{ Auth::user()->name }}!</span></h4>
                        <p class="text-muted mb-3">Welcome to your HR Dashboard. Manage your daily presence here.</p>
                        
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-4">
                                <h2 id="live-time" class="text-dark font-weight-bold mb-0">00:00:00 AM</h2>
                                <p class="text-muted small mb-0">{{ date('l, d M Y') }}</p>
                            </div>
                            @php $effectiveShift = Auth::user()->getEffectiveShift(); @endphp
                            @if($effectiveShift)
                                <div class="border-left pl-3">
                                    <p class="mb-0 text-primary small">Office Time</p>
                                    <p class="font-weight-bold mb-0">
                                        {{ $effectiveShift->name }} <br>
                                        ({{ \App\Services\HelperService::formatTime($effectiveShift->start_time) }} - {{ \App\Services\HelperService::formatTime($effectiveShift->end_time) }})
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 text-right">
                        @php
                            $todayAttendance = \App\Models\Attendance::where('user_id', Auth::id())
                                ->where('date', date('Y-m-d'))
                                ->first();
                            $hasCheckIn = $todayAttendance && $todayAttendance->logs->where('type', 'check_in')->isNotEmpty();
                        @endphp

                        <div id="location-error" class="alert alert-danger" style="display:none; text-align:left;">
                            Mock location detected or location blocked! Attendance denied.
                        </div>

                        @if(!$hasCheckIn)
                            @can('attendance.check_in')
                            <form action="{{ route('attendances.check_in') }}" method="POST" id="checkInForm">
                                @csrf
                                <input type="hidden" name="latitude" id="lat">
                                <input type="hidden" name="longitude" id="lng">
                                <button type="button" onclick="handleLoc('checkInForm')" class="btn btn-lg btn-gradient-success btn-icon-text px-5 py-3 shadow-sm">
                                    <i class="mdi mdi-login-variant btn-icon-prepend"></i> CHECK IN NOW
                                </button>
                                <p class="mt-2 text-muted small italic">Ready to start your work day?</p>
                            </form>
                            @else
                                <p class="text-muted small">You don't have permission to record attendance.</p>
                            @endcan
                        @else
                            <div class="d-flex flex-column align-items-end">
                                @can('attendance.check_out')
                                <form action="{{ route('attendances.check_out') }}" method="POST" id="checkOutForm">
                                    @csrf
                                    <input type="hidden" name="latitude" id="lat2">
                                    <input type="hidden" name="longitude" id="lng2">
                                    <button type="button" onclick="handleLoc('checkOutForm')" class="btn btn-lg btn-gradient-danger btn-icon-text px-5 py-3 shadow-sm">
                                        <i class="mdi mdi-logout-variant btn-icon-prepend"></i> CHECK OUT AGAIN
                                    </button>
                                </form>
                                @endcan
                                
                                <div class="mt-3 text-right">
                                    <h6 class="mb-2 font-weight-bold">Today's Activity:</h6>
                                    <ul class="list-unstyled small">
                                        @foreach($todayAttendance->logs->sortBy('time') as $act)
                                            <li>
                                                <i class="mdi {{ $act->type == 'check_in' ? 'mdi-check-circle text-success' : 'mdi-clock-out text-danger' }}"></i>
                                                {{ strtoupper(str_replace('_', ' ', $act->type)) }} at {{ \App\Services\HelperService::formatTime($act->time) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@can('dashboard.real_time_overview')
<div class="row">
    <!-- Real-time Attendance Stats (Modern Section) -->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Today's Real-time Overview</h4>
                <div class="row text-center">
                    <div class="col-6 col-md-2 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-primary">
                            <p class="text-muted small font-weight-bold mb-1">TOTAL STAFF</p>
                            <h3 class="text-primary mb-0">{{ $attendanceStats['total_employees'] }}</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-success">
                            <p class="text-muted small font-weight-bold mb-1">PRESENT</p>
                            <h3 class="text-success mb-0">{{ $attendanceStats['present'] }}</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-warning">
                            <p class="text-muted small font-weight-bold mb-1">LATE TODAY</p>
                            <h3 class="text-warning mb-0">{{ $attendanceStats['late'] }}</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-info">
                            <p class="text-muted small font-weight-bold mb-1">ON LEAVE</p>
                            <h3 class="text-info mb-0">{{ $attendanceStats['on_leave'] }}</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-danger">
                            <p class="text-muted small font-weight-bold mb-1">ABSENT</p>
                            <h3 class="text-danger mb-0">{{ $attendanceStats['absent'] }}</h3>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-secondary">
                            <p class="text-muted small font-weight-bold mb-1">REAL-TIME RATE</p>
                            <h3 class="text-secondary mb-0">{{ $attendanceStats['attendance_rate'] }}%</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Leave Processing Overview</h4>
                <div class="row text-center">
                    <div class="col-4 col-md-4 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-warning">
                            <p class="text-muted small font-weight-bold mb-1">PENDING REQUESTS</p>
                            <h3 class="text-warning mb-0">{{ $attendanceStats['pending_leaves'] }}</h3>
                        </div>
                    </div>
                    <div class="col-4 col-md-4 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-success">
                            <p class="text-muted small font-weight-bold mb-1">APPROVED (YTD)</p>
                            <h3 class="text-success mb-0">{{ $attendanceStats['approved_leaves'] }}</h3>
                        </div>
                    </div>
                    <div class="col-4 col-md-4 mb-3">
                        <div class="p-3 bg-light rounded border-bottom border-danger">
                            <p class="text-muted small font-weight-bold mb-1">REJECTED (YTD)</p>
                            <h3 class="text-danger mb-0">{{ $attendanceStats['rejected_leaves'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@can('dashboard.statistics_cards')
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                <h4 class="font-weight-normal mb-3">Total Employees
                    <i class="mdi mdi-account-multiple mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $totalUsers }}</h2>
                <h6 class="card-text">Total active/inactive staff</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                <h4 class="font-weight-normal mb-3">Account Roles
                    <i class="mdi mdi-shield-check mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $totalRoles }}</h2>
                <h6 class="card-text">Defined access levels</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                                    
                <h4 class="font-weight-normal mb-3">Total Monthly Payroll
                    <i class="mdi mdi-currency-usd mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">${{ number_format($totalSalary, 2) }}</h2>
                <h6 class="card-text">Combined base salaries</h6>
            </div>
        </div>
    </div>
</div>
@endcan

@can('dashboard.employee_personal_stats')
<div class="row">
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                <h4 class="font-weight-normal mb-3">My Presence ({{ date('F') }})
                    <i class="mdi mdi-check-circle mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $personalStats['present'] }} Days</h2>
                <h6 class="card-text">Total presence this month</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-warning card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                <h4 class="font-weight-normal mb-3">Late Records
                    <i class="mdi mdi-clock-alert mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $personalStats['late'] }} Times</h2>
                <h6 class="card-text">Punctuality tracking</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                <h4 class="font-weight-normal mb-3">Available Leave
                    <i class="mdi mdi-calendar-check mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $personalStats['remaining_leave'] }} Days</h2>
                <h6 class="card-text">Total annual balance</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white shadow">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                <h4 class="font-weight-normal mb-3">Pending Requests
                    <i class="mdi mdi-file-document mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $personalStats['pending_requests'] }}</h2>
                <h6 class="card-text">Waiting for approval</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">My Recent Activity</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="text-center text-primary">
                            <tr>
                                <th> Date </th>
                                <th> Check-In </th>
                                <th> Check-Out </th>
                                <th> Status </th>
                                <th> Total Hours </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myRecentLogs as $att)
                            <tr class="text-center">
                                <td class="font-weight-bold"> {{ \App\Services\HelperService::formatDate($att->date) }} </td>
                                <td> 
                                    @php $checkIn = $att->logs->where('type', 'check_in')->first(); @endphp
                                    <span class="text-success">{{ $checkIn ? \App\Services\HelperService::formatTime($checkIn->time) : '--:--' }}</span>
                                </td>
                                <td>
                                    @php $checkOut = $att->logs->where('type', 'check_out')->last(); @endphp
                                    <span class="text-danger">{{ $checkOut ? \App\Services\HelperService::formatTime($checkOut->time) : '--:--' }}</span>
                                </td>
                                <td>
                                    @if($att->status == 'present')
                                        <label class="badge badge-gradient-success">PRESENT</label>
                                    @elseif($att->status == 'late')
                                        <label class="badge badge-gradient-warning">LATE</label>
                                    @else
                                        <label class="badge badge-gradient-danger">ABSENT</label>
                                    @endif
                                </td>
                                <td> 
                                    <span class="font-weight-bold text-dark">{{ $att->total_hours ?? '0.00' }}</span> <small>hrs</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-4 italic">No attendance records found for this period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@can('dashboard.recent_employees')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Recent Employees</h4>
                <div class="table-responsive">
                    <table class="table hover">
                        <thead>
                            <tr>
                                <th> Employee </th>
                                <th> Designation </th>
                                <th> Status </th>
                                <th> Joined </th>
                                <th> Email </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>
                                    @if($user->image)
                                        <img src="{{ asset('storage/' . $user->image) }}" class="mr-2" alt="image">
                                    @else
                                        <img src="{{ asset('backend/images/faces/face1.jpg') }}" class="mr-2" alt="image">
                                    @endif
                                    {{ $user->name }}
                                </td>
                                <td> {{ $user->designation->name ?? 'N/A' }} </td>
                                <td>
                                    {!! \App\Services\HelperService::getStatusBadge($user->status) !!}
                                </td>
                                <td> {{ $user->joining_date ?? 'N/A' }} </td>
                                <td> {{ $user->email }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@push('scripts')
<script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('live-time').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function handleLoc(formId) {
        let errorDiv = document.getElementById('location-error');
        errorDiv.style.display = 'none';

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // Mock Location Check
                if (position.mocked === true || (position.coords.accuracy === 0)) {
                    errorDiv.innerText = "Fake location app detected! Please disable it.";
                    errorDiv.style.display = 'block';
                    return;
                }

                if (document.getElementById('lat')) document.getElementById('lat').value = position.coords.latitude;
                if (document.getElementById('lng')) document.getElementById('lng').value = position.coords.longitude;
                if (document.getElementById('lat2')) document.getElementById('lat2').value = position.coords.latitude;
                if (document.getElementById('lng2')) document.getElementById('lng2').value = position.coords.longitude;
                document.getElementById(formId).submit();
            }, function(error) {
                console.warn("Geolocation blocked/error: ", error.message);
                // In production, you might want to REQUIRE GPS, but for now we allow submission
                document.getElementById(formId).submit(); 
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush
@endsection
