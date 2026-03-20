@extends('backend.layouts.app')

@section('title', 'Attendance Logs - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-check"></i>                 
        </span>
        All Attendance Logs
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attendance</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Daily Presence Logs</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        @include('backend.components.search_box', ['action' => route('attendances.index'), 'placeholder' => 'Search by name or date...'])
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>In Time</th>
                                <th>Late</th>
                                <th>Out Time</th>
                                <th>Early/OT</th>
                                <th>Stay Duration</th>
                                <th>History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $att)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $att->user->name }}</td>
                                <td>{{ \App\Services\HelperService::formatDate($att->date) }}</td>
                                <td class="text-success font-weight-bold">
                                    {{ \App\Services\HelperService::formatTime($att->check_in_time) }}
                                </td>
                                <td>
                                    @if($att->late_minutes > 0)
                                        <span class="text-danger small">+{{ $att->late_minutes }}m</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-danger font-weight-bold">
                                    {{ \App\Services\HelperService::formatTime($att->check_out_time) ?? '-' }}
                                </td>
                                <td>
                                    @if($att->early_leaving_minutes > 0)
                                        <span class="text-warning small">-{{ $att->early_leaving_minutes }}m (Early)</span>
                                    @elseif($att->overtime_minutes > 0)
                                        <span class="text-success small">+{{ $att->overtime_minutes }}m (OT)</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $att->stay_minutes ? floor($att->stay_minutes/60).'h '.($att->stay_minutes%60).'m' : '-' }}
                                    <br>
                                    @php
                                        $statusText = 'ABSENT';
                                        $badgeClass = 'badge-gradient-danger';
                                        if($att->status == 1) { $statusText = 'PRESENT'; $badgeClass = 'badge-gradient-success'; }
                                        elseif($att->status == 2) { $statusText = 'LATE'; $badgeClass = 'badge-gradient-warning'; }
                                        elseif($att->status == 3) { $statusText = 'HALF DAY'; $badgeClass = 'badge-gradient-info'; }
                                        elseif($att->status == 4) { $statusText = 'LEAVE'; $badgeClass = 'badge-gradient-primary'; }
                                    @endphp
                                    <label class="badge {{ $badgeClass }} badge-sm" style="font-size: 0.7rem;">
                                        {{ $statusText }}
                                    </label>
                                </td>
                                <td>
                                    @php
                                        $statusText = 'ABSENT';
                                        $badgeClass = 'badge-gradient-danger';
                                        
                                        if($att->status == 1) { $statusText = 'PRESENT'; $badgeClass = 'badge-gradient-success'; }
                                        elseif($att->status == 2) { $statusText = 'LATE'; $badgeClass = 'badge-gradient-warning'; }
                                        elseif($att->status == 3) { $statusText = 'HALF DAY'; $badgeClass = 'badge-gradient-info'; }
                                        elseif($att->status == 4) { $statusText = 'LEAVE'; $badgeClass = 'badge-gradient-primary'; }
                                    @endphp
                                    <label class="badge {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </label>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-gradient-info btn-sm" data-toggle="modal" data-target="#historyModal{{ $att->id }}">
                                        <i class="mdi mdi-history"></i> Logs ({{ $att->logs->count() }})
                                    </button>

                                    <!-- History Modal -->
                                    <div class="modal fade" id="historyModal{{ $att->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-info text-white">
                                                    <h5 class="modal-title">Attendance History - {{ $att->user->name }}</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3"><strong>Date:</strong> {{ \App\Services\HelperService::formatDate($att->date) }}</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Event</th>
                                                                    <th>Time</th>
                                                                    <th>IP</th>
                                                                    <th>Map</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($att->logs->sortBy('time') as $log)
                                                                <tr>
                                                                    <td>
                                                                        <span class="badge {{ $log->type == 'check_in' ? 'badge-success' : 'badge-danger' }}">
                                                                            {{ strtoupper(str_replace('_', ' ', $log->type)) }}
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ \App\Services\HelperService::formatTime($log->time) }}</td>
                                                                    <td><small>{{ $log->ip }}</small></td>
                                                                    <td>
                                                                        @if($log->latitude)
                                                                            <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank">
                                                                                <i class="mdi mdi-map-marker"></i>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
