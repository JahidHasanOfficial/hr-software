@extends('backend.layouts.app')

@section('title', 'Candidates - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-group"></i>                 
        </span>
        Candidates
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recruitment</li>
            <li class="breadcrumb-item active" aria-current="page">Candidates</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Candidate List</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        <form action="{{ route('recruitment.candidates.index') }}" method="GET" class="mr-3 d-flex">
                            <select name="job_post_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="">All Job Posts</option>
                                @foreach($jobPosts as $post)
                                    <option value="{{ $post->id }}" {{ request('job_post_id') == $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                                @endforeach
                            </select>
                        </form>
                        <a href="{{ route('recruitment.candidates.create') }}" class="btn btn-gradient-primary btn-sm btn-fw">Add Candidate</a>
                        <a href="{{ route('recruitment.candidates.kanban') }}" class="btn btn-outline-info btn-sm btn-fw ml-2">Kanban View</a>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Job Applied For</th>
                                <th>Stage</th>
                                <th>Source</th>
                                <th>Exp (Years)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidates as $candidate)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $candidate->first_name }} {{ $candidate->last_name }}</td>
                                <td>{{ $candidate->email }}</td>
                                <td>{{ $candidate->jobPost->title }}</td>
                                <td>
                                    <label class="badge badge-outline-primary">{{ $candidate->stage->name }}</label>
                                </td>
                                <td>{{ $candidate->source ?? 'N/A' }}</td>
                                <td>{{ $candidate->experience_years ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('recruitment.candidates.show', $candidate->id) }}" class="btn btn-sm btn-gradient-info p-2 mr-1" title="View"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{ route('recruitment.candidates.edit', $candidate->id) }}" class="btn btn-sm btn-gradient-warning p-2 mr-1" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('recruitment.candidates.destroy', $candidate->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Are you sure?')"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No candidates found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $candidates->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
