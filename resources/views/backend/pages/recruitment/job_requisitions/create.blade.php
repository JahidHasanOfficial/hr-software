@extends('backend.layouts.app')

@section('title', 'Create Job Requisition - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-plus"></i>                 
        </span>
        Create Job Requisition
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.job-requisitions.index') }}">Job Requisitions</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary mb-4">Requisition Details</h4>
                <form action="{{ route('recruitment.job-requisitions.store') }}" method="POST" class="forms-sample">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Job Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="e.g. Senior Laravel Developer" value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="headcount">No. of Vacancies <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('headcount') is-invalid @enderror" id="headcount" name="headcount" placeholder="e.g. 2" value="{{ old('headcount') }}" min="1" required>
                                @error('headcount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department_id">Department <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation_id">Requested Designation <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('designation_id') is-invalid @enderror" id="designation_id" name="designation_id" required>
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                    @endforeach
                                </select>
                                @error('designation_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="urgency_level">Urgency Level <span class="text-danger">*</span></label>
                                <select class="form-control @error('urgency_level') is-invalid @enderror" id="urgency_level" name="urgency_level" required>
                                    <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('urgency_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('urgency_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('urgency_level')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="justification">Justification/Reason for Hiring</label>
                        <textarea class="form-control @error('justification') is-invalid @enderror" id="justification" name="justification" rows="4" placeholder="Briefly explain why this position is needed...">{{ old('justification') }}</textarea>
                        @error('justification')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="budget_details">Budget Details (if any)</label>
                        <textarea class="form-control @error('budget_details') is-invalid @enderror" id="budget_details" name="budget_details" rows="2" placeholder="Mention budget allocation, salary range, etc.">{{ old('budget_details') }}</textarea>
                        @error('budget_details')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary mr-2">Submit Requisition</button>
                        <a href="{{ route('recruitment.job-requisitions.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
