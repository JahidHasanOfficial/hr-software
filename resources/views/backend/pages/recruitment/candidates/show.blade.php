@extends('backend.layouts.app')

@section('title', 'Candidate Details - ' . $candidate->first_name . ' ' . $candidate->last_name)

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-card-details"></i>                 
        </span>
        Candidate Profile
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.candidates.index') }}">Candidates</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $candidate->first_name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($candidate->first_name . ' ' . $candidate->last_name) }}&size=128&background=random" class="img-lg rounded-circle mb-2" alt="profile image">
                    <h4>{{ $candidate->first_name }} {{ $candidate->last_name }}</h4>
                    <p class="text-muted">{{ $candidate->email }}</p>
                    <p class="text-muted mb-0">{{ $candidate->phone }}</p>
                </div>
                
                <hr>
                
                <div class="text-left mt-4">
                    <p class="text-muted font-weight-bold mb-1">Status</p>
                    @php
                        $statusClass = [
                            'active' => 'success',
                            'hired' => 'primary',
                            'rejected' => 'danger'
                        ][$candidate->status] ?? 'secondary';
                    @endphp
                    <label class="badge badge-{{ $statusClass }}">{{ ucfirst($candidate->status) }}</label>
                    <hr>
                    <p class="text-muted font-weight-bold mb-1">Stage</p>
                    <label class="badge badge-gradient-info">{{ $candidate->stage->name }}</label>
                    <hr>
                    <p class="text-muted font-weight-bold mb-1">Source</p>
                    <p class="mb-0">{{ $candidate->source ?: 'Not specified' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detailed Information</h4>
                
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted">Applied For</label>
                        <p class="font-weight-bold">
                            <a href="{{ route('recruitment.job-posts.show', $candidate->job_post_id) }}">{{ $candidate->jobPost->title }}</a>
                        </p>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted">Total Experience</label>
                        <p class="font-weight-bold">{{ $candidate->experience_years }} Years</p>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted">Expected Salary</label>
                        <p class="font-weight-bold text-success">{{ number_format($candidate->expected_salary) }} BDT</p>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="text-muted">Applied On</label>
                        <p class="font-weight-bold">{{ $candidate->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h5 class="card-title">Resume / CV</h5>
                    @if($candidate->resume_path)
                        <div class="p-3 bg-light border rounded d-flex justify-content-between align-items-center">
                            <span><i class="mdi mdi-file-pdf text-danger mr-2"></i> {{ basename($candidate->resume_path) }}</span>
                            <a href="{{ asset('storage/' . $candidate->resume_path) }}" target="_blank" class="btn btn-sm btn-gradient-primary">Download</a>
                        </div>
                    @else
                        <p class="text-muted">No resume uploaded.</p>
                    @endif
                </div>

                <hr>

                @if($candidate->status == 'rejected')
                <div class="mb-4">
                    <h5 class="card-title text-danger">Rejection Reason</h5>
                    <p class="p-3 border rounded border-danger text-danger bg-light">
                        {{ $candidate->rejection_reason ?: 'No reason provided.' }}
                    </p>
                </div>
                @endif
                
                <div class="template-demo mt-4">
                    <a href="{{ route('recruitment.candidates.edit', $candidate->id) }}" class="btn btn-gradient-warning mr-2">Edit Candidate</a>
                    <a href="{{ route('recruitment.candidates.kanban') }}" class="btn btn-gradient-info">Manage Pipeline</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
