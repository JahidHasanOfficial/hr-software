@extends('backend.layouts.app')

@section('title', 'Edit Candidate - ' . $candidate->first_name)

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-edit"></i>                 
        </span>
        Edit Candidate Information
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.candidates.index') }}">Candidates</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Candidate</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Candidate Details</h4>
                <p class="card-description"> Update the information for <strong>{{ $candidate->first_name }} {{ $candidate->last_name }}</strong> </p>
                
                <form action="{{ route('recruitment.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data" class="forms-sample">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="first_name" value="{{ old('first_name', $candidate->first_name) }}" required>
                                @error('first_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="last_name" value="{{ old('last_name', $candidate->last_name) }}" required>
                                @error('last_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $candidate->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" value="{{ old('phone', $candidate->phone) }}" required>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_post_id">Applied For <span class="text-danger">*</span></label>
                                <select name="job_post_id" class="form-control" id="job_post_id" required>
                                    @foreach($jobPosts as $post)
                                        <option value="{{ $post->id }}" {{ old('job_post_id', $candidate->job_post_id) == $post->id ? 'selected' : '' }}>
                                            {{ $post->title }} ({{ $post->job_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="candidate_stage_id">Current Stage <span class="text-danger">*</span></label>
                                <select name="candidate_stage_id" class="form-control" id="candidate_stage_id" required>
                                    @foreach($stages as $stage)
                                        <option value="{{ $stage->id }}" {{ old('candidate_stage_id', $candidate->candidate_stage_id) == $stage->id ? 'selected' : '' }}>
                                            {{ $stage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="experience_years">Experience (Years)</label>
                                <input type="number" name="experience_years" step="0.1" class="form-control" id="experience_years" value="{{ old('experience_years', $candidate->experience_years) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expected_salary">Expected Salary (Monthly BDT)</label>
                                <input type="number" name="expected_salary" class="form-control" id="expected_salary" value="{{ old('expected_salary', $candidate->expected_salary) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="source">Application Source</label>
                        <input type="text" name="source" class="form-control" id="source" value="{{ old('source', $candidate->source) }}" placeholder="e.g. LinkedIn, Referral">
                    </div>

                    <div class="form-group">
                        <label>Update Resume / CV</label>
                        <input type="file" name="resume" class="form-control-file">
                        @if($candidate->resume_path)
                            <small class="text-muted d-block mt-2">Current file: <a href="{{ asset('storage/' . $candidate->resume_path) }}" target="_blank">View Resume</a></small>
                        @endif
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-gradient-primary mr-2">Update Profile</button>
                        <a href="{{ route('recruitment.candidates.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
