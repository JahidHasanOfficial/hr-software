@extends('backend.layouts.app')

@section('title', 'Create Job Post - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-plus-circle"></i>                 
        </span>
        Create Job Post
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.job-posts.index') }}">Job Posts</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary mb-4">Job Advertisement Details</h4>
                <form action="{{ route('recruitment.job-posts.store') }}" method="POST" class="forms-sample">
                    @csrf
                    
                    @if(request()->has('requisition_id'))
                        <input type="hidden" name="job_requisition_id" value="{{ request('requisition_id') }}">
                        <div class="alert alert-info">
                            Creating post for Approved Requisition: <strong>{{ $requisition->title ?? 'N/A' }}</strong>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Job Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="e.g. Senior Laravel Developer" value="{{ old('title', $requisition->title ?? '') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employment_type">Employment Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('employment_type') is-invalid @enderror" id="employment_type" name="employment_type" required>
                                    <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="e.g. Dhaka, Bangladesh (Remote/On-site)" value="{{ old('location') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary_min">Salary Min (Optional)</label>
                                <input type="number" class="form-control" id="salary_min" name="salary_min" placeholder="e.g. 50000" value="{{ old('salary_min') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary_max">Salary Max (Optional)</label>
                                <input type="number" class="form-control" id="salary_max" name="salary_max" placeholder="e.g. 80000" value="{{ old('salary_max') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Job Description (JD) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-check form-check-flat form-check-primary mb-4">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="publish" value="1" {{ old('publish') ? 'checked' : '' }}> Publish immediately <i class="input-helper"></i>
                        </label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary mr-2">Create Job Post</button>
                        <a href="{{ route('recruitment.job-posts.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'lists link image media table help wordcount',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | help',
        menubar: false,
    });
</script>
@endpush
