@extends('backend.layouts.app')

@section('title', $post->title . ' - Job Post Details')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-briefcase"></i>                 
        </span>
        Job Post Details
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.job-posts.index') }}">Job Posts</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $post->job_code }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">{{ $post->title }}</h4>
                    <div>
                        @if($post->is_published)
                            <label class="badge badge-success">Published</label>
                        @else
                            <label class="badge badge-warning">Draft</label>
                            @can('job-post.publish')
                            <form action="{{ route('recruitment.job-posts.publish', $post->id) }}" method="POST" class="d-inline ml-2">
                                @csrf
                                <button type="submit" class="btn btn-gradient-success btn-xs">Publish Now</button>
                            </form>
                            @endcan
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Description</h5>
                    <div class="p-3 bg-light border rounded">
                        {!! $post->description !!}
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded mb-3">
                            <h6 class="text-muted mb-2">Employment Type</h6>
                            <p class="mb-0 font-weight-bold">{{ ucfirst($post->employment_type) }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded mb-3">
                            <h6 class="text-muted mb-2">Location</h6>
                            <p class="mb-0 font-weight-bold">{{ $post->location ?: 'Not specified' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded mb-3">
                            <h6 class="text-muted mb-2">Salary Range</h6>
                            <p class="mb-0 font-weight-bold">
                                {{ number_format($post->salary_min) }} - {{ number_format($post->salary_max) }} BDT
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded mb-3">
                            <h6 class="text-muted mb-2">Expiry Date</h6>
                            <p class="mb-0 font-weight-bold text-{{ $post->expiry_date < now() ? 'danger' : 'success' }}">
                                {{ $post->expiry_date ? \Carbon\Carbon::parse($post->expiry_date)->format('M d, Y') : 'No dynamic expiry' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Quick Actions</h4>
                <div class="template-demo">
                    <a href="{{ route('recruitment.job-posts.edit', $post->id) }}" class="btn btn-gradient-warning btn-block">Edit Post</a>
                    <a href="{{ route('recruitment.candidates.index', ['job_post_id' => $post->id]) }}" class="btn btn-gradient-info btn-block">View Candidates</a>
                    
                    <hr>
                    
                    @if($post->jobRequisition)
                        <h6 class="mt-4">Linked Requisition</h6>
                        <div class="p-3 border rounded bg-light">
                            <p class="mb-1 text-muted">ID: #{{ $post->jobRequisition->id }}</p>
                            <p class="mb-1"><strong>{{ $post->jobRequisition->title }}</strong></p>
                            <a href="{{ route('recruitment.job-requisitions.show', $post->jobRequisition->id) }}" class="btn btn-link btn-sm p-0">View Requisition</a>
                        </div>
                    @endif

                    <form action="{{ route('recruitment.job-posts.destroy', $post->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-block" onclick="return confirm('Are you sure you want to delete this job post?')">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
