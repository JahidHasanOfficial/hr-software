@extends('backend.layouts.app')

@section('title', 'Manage Designations - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-tie"></i>                 
        </span>
        Organization: Designations
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Designations</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Designation List</h4>
                    <a href="{{ route('designations.create') }}" class="btn btn-gradient-primary btn-fw">Add Pos</a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dept > Branch</th>
                                <th>Position Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designations as $desig)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $desig->department->name }} ({{ $desig->department->branch->name }})</td>
                                <td>{{ $desig->name }}</td>
                                <td>
                                    <label class="badge {{ $desig->status == 'active' ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                        {{ strtoupper($desig->status) }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('designations.edit', $desig->id) }}" class="btn btn-sm btn-gradient-warning">Edit</a>
                                    <form action="{{ route('designations.destroy', $desig->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger" onclick="return confirm('Delete designation?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $designations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
