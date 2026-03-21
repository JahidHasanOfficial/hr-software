@extends('backend.layouts.app')

@section('title', 'Job Posts - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bullhorn"></i>                 
        </span>
        Job Posts
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recruitment</li>
            <li class="breadcrumb-item active" aria-current="page">Job Posts</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Active Job Posts</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        <a href="{{ route('recruitment.job-posts.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">Create New Post</a>
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
                                <th>Code</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Expiry</th>
                                <th>Candidates</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ $post->job_code }}</code></td>
                                <td>{{ $post->title }}</td>
                                <td>{{ ucfirst($post->employment_type) }}</td>
                                <td>{{ $post->expiry_date ? $post->expiry_date : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-pill badge-info">{{ $post->candidates_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($post->is_published)
                                        <label class="badge badge-success">Published</label>
                                    @else
                                        <label class="badge badge-warning">Draft</label>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('recruitment.job-posts.show', $post->id) }}" class="btn btn-sm btn-gradient-info p-2 mr-1" title="View"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{ route('recruitment.job-posts.edit', $post->id) }}" class="btn btn-sm btn-gradient-warning p-2 mr-1" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        @if(!$post->is_published)
                                            <form action="{{ route('recruitment.job-posts.publish', $post->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-gradient-success p-2 mr-1" title="Publish"><i class="mdi mdi-upload"></i></button>
                                            </form>
                                        @endif
                                        <form action="{{ route('recruitment.job-posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Are you sure?')"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No job posts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
