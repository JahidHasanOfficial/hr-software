@extends('backend.layouts.app')

@section('title', 'My Attendance Logs - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-card-details"></i>                 
        </span>
        My Attendance History
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Logs</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Presence Summary for {{ Auth::user()->name }}</h4>
                
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="bg-light">
                                <th>Date</th>
                                <th>First In</th>
                                <th>Late</th>
                                <th>Last Out</th>
                                <th>Early/OT</th>
                                <th>Duration</th>
                                <th>History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ \App\Services\HelperService::formatDate($log->date) }}</td>
                                <td class="text-success font-weight-bold">{{ \App\Services\HelperService::formatTime($log->check_in_time) }}</td>
                                <td>
                                    @if($log->late_minutes > 0)
                                        <span class="text-danger small">+{{ $log->late_minutes }}m</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-danger font-weight-bold">{{ \App\Services\HelperService::formatTime($log->check_out_time) ?? '-' }}</td>
                                <td>
                                    @if($log->early_leaving_minutes > 0)
                                        <span class="text-warning small">-{{ $log->early_leaving_minutes }}m (Early)</span>
                                    @elseif($log->overtime_minutes > 0)
                                        <span class="text-success small">+{{ $log->overtime_minutes }}m (OT)</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $log->stay_minutes ? floor($log->stay_minutes/60).'h '.($log->stay_minutes%60).'m' : '-' }}
                                    <br>
                                    @php
                                        $statusText = 'ABSENT';
                                        $badgeClass = 'badge-gradient-danger';
                                        if($log->status == 1) { $statusText = 'PRESENT'; $badgeClass = 'badge-gradient-success'; }
                                        elseif($log->status == 2) { $statusText = 'LATE'; $badgeClass = 'badge-gradient-warning'; }
                                        elseif($log->status == 3) { $statusText = 'HALF DAY'; $badgeClass = 'badge-gradient-info'; }
                                        elseif($log->status == 4) { $statusText = 'LEAVE'; $badgeClass = 'badge-gradient-primary'; }
                                    @endphp
                                    <label class="badge {{ $badgeClass }} badge-sm" style="font-size: 0.7rem;">
                                        {{ $statusText }}
                                    </label>
                                </td>
                                <td>
                                    <button class="btn btn-gradient-info btn-xs" data-toggle="modal" data-target="#historyMod{{ $log->id }}">
                                        Logs ({{ $log->logs->count() }})
                                    </button>

                                    <!-- My History Modal -->
                                    <div class="modal fade" id="historyMod{{ $log->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content text-left">
                                                <div class="modal-header bg-gradient-info text-white p-3">
                                                    <h5 class="modal-title font-weight-bold">Daily Events - {{ \App\Services\HelperService::formatDate($log->date) }}</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Type</th>
                                                                    <th>Time</th>
                                                                    <th class="text-center">Location</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($log->logs->sortBy('time') as $event)
                                                                <tr>
                                                                    <td>
                                                                        <label class="badge {{ $event->type == 'check_in' ? 'badge-gradient-success' : 'badge-gradient-danger' }} px-2 py-1">
                                                                            {{ strtoupper(str_replace('_', ' ', $event->type)) }}
                                                                        </label>
                                                                    </td>
                                                                    <td class="font-weight-bold">{{ \App\Services\HelperService::formatTime($event->time) }}</td>
                                                                    <td class="text-center">
                                                                        @if($event->latitude)
                                                                            <a href="https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}" target="_blank" class="text-info">
                                                                                <i class="mdi mdi-map-marker-radius mdi-18px"></i> View
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
                                                <div class="modal-footer p-2">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
