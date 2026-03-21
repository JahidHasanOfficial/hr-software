@extends('backend.layouts.app')

@section('title', 'Edit Job Requisition - ' . $requisition->title)

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-search"></i>                 
        </span>
        Edit Job Requisition
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.job-requisitions.index') }}">Job Requisitions</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Requisition</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Requisition Details</h4>
                <p class="card-description"> Update requisition for <strong>{{ $requisition->title }}</strong> </p>
                
                <form action="{{ route('recruitment.job-requisitions.update', $requisition->id) }}" method="POST" class="forms-sample">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title', $requisition->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department_id">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control" id="department_id" required>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $requisition->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation_id">Designation <span class="text-danger">*</span></label>
                                <select name="designation_id" class="form-control" id="designation_id" required>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id', $requisition->designation_id) == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="headcount">Required Headcount <span class="text-danger">*</span></label>
                                <input type="number" name="headcount" class="form-control" id="headcount" value="{{ old('headcount', $requisition->headcount) }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="urgency_level">Urgency Level <span class="text-danger">*</span></label>
                                <select name="urgency_level" class="form-control" id="urgency_level" required>
                                    <option value="low" {{ old('urgency_level', $requisition->urgency_level) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('urgency_level', $requisition->urgency_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('urgency_level', $requisition->urgency_level) == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('urgency_level', $requisition->urgency_level) == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="justification">Justification / Reason</label>
                        <textarea name="justification" class="form-control" id="justification" rows="4">{{ old('justification', $requisition->justification) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="budget_details">Budget Details / Approved Range</label>
                        <textarea name="budget_details" class="form-control" id="budget_details" rows="2">{{ old('budget_details', $requisition->budget_details) }}</textarea>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-gradient-primary mr-2">Update Requisition</button>
                        <a href="{{ route('recruitment.job-requisitions.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
