@extends('backend.layouts.app')

@section('title', 'Edit Job Post - ' . $post->title)

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-briefcase"></i>                 
        </span>
        Edit Job Post
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.job-posts.index') }}">Job Posts</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Post</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Job Details</h4>
                <p class="card-description"> Update the information for <strong>{{ $post->job_code }}</strong> </p>
                
                <form action="{{ route('recruitment.job-posts.update', $post->id) }}" method="POST" class="forms-sample">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Job Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title', $post->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employment_type">Employment Type</label>
                                <select name="employment_type" class="form-control" id="employment_type">
                                    <option value="full-time" {{ old('employment_type', $post->employment_type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="part-time" {{ old('employment_type', $post->employment_type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="contract" {{ old('employment_type', $post->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="internship" {{ old('employment_type', $post->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Job Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="10">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary_min">Minimum Salary (Monthly)</label>
                                <input type="number" name="salary_min" class="form-control" id="salary_min" value="{{ old('salary_min', $post->salary_min) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary_max">Maximum Salary (Monthly)</label>
                                <input type="number" name="salary_max" class="form-control" id="salary_max" value="{{ old('salary_max', $post->salary_max) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" name="location" class="form-control" id="location" value="{{ old('location', $post->location) }}" placeholder="e.g. Dhaka, Remote">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control" id="expiry_date" value="{{ old('expiry_date', $post->expiry_date ? \Carbon\Carbon::parse($post->expiry_date)->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-gradient-primary mr-2">Update Job Post</button>
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
    plugins: 'lists link image table code help wordcount',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat | help'
  });
</script>
@endpush
