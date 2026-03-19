@extends('backend.layouts.app')

@section('title', 'Manage Branches - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-map-marker-radius"></i>                 
        </span>
        Organization: Branches
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Branches</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Branch List</h4>
                    <a href="{{ route('branches.create') }}" class="btn btn-gradient-primary btn-fw">Add Branch</a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company</th>
                                <th>Branch Name</th>
                                <th>Location</th>
                                <th>Email/Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $branch->company->name }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->location ?? 'N/A' }}</td>
                                <td>{{ $branch->email }} <br> {{ $branch->phone }}</td>
                                <td>
                                    <label class="badge {{ $branch->status == 'active' ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                        {{ strtoupper($branch->status) }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-gradient-warning">Edit</a>
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger" onclick="return confirm('Delete branch?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $branches->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
