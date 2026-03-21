@extends('backend.layouts.app')

@section('title', 'Interview Schedule - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-clock"></i>                 
        </span>
        Interview Schedule
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recruitment</li>
            <li class="breadcrumb-item active" aria-current="page">Interviews</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Scheduled Interviews</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        <a href="{{ route('recruitment.interviews.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">Schedule Interview</a>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Candidate</th>
                                <th>Interview Type</th>
                                <th>Date & Time</th>
                                <th>Location/Link</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($interviews as $interview)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($interview->candidate->first_name . ' ' . $interview->candidate->last_name) }}&size=30&background=random" class="mr-2 rounded-circle" alt="image">
                                        {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                    </div>
                                </td>
                                <td>{{ ucfirst($interview->interview_type) }}</td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($interview->scheduled_at)->format('M d, Y') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($interview->scheduled_at)->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if(str_contains(strtolower($interview->location), 'http'))
                                        <a href="{{ $interview->location }}" target="_blank" class="text-info"><i class="mdi mdi-video"></i> Online Link</a>
                                    @else
                                        {{ $interview->location ?: 'Not set' }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'scheduled' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            'rescheduled' => 'warning'
                                        ][$interview->status] ?? 'secondary';
                                    @endphp
                                    <label class="badge badge-{{ $statusClass }} text-white">{{ ucfirst($interview->status) }}</label>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('recruitment.interviews.edit', $interview->id) }}" class="btn btn-sm btn-gradient-warning p-2 mr-1" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('recruitment.interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Are you sure?')"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No interviews scheduled.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $interviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
