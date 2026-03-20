@extends('backend.layouts.app')

@section('title', 'Manage Departments - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-group"></i>                 
        </span>
        Organization: Departments
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Departments</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Department List</h4>
                    <a href="{{ route('departments.create') }}" class="btn btn-gradient-primary btn-fw">Add Dept</a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company > Branch</th>
                                <th>Department Name</th>
                                <th>Default Shift</th>
                                <th class="text-center">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $dept)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dept->branch->company->name ?? '-' }} > {{ $dept->branch->name ?? '-' }}</td>
                                <td class="font-weight-bold">{{ $dept->name }}</td>
                                <td>
                                    {{ $dept->shift ? $dept->shift->name : 'NONE' }}
                                </td>
                                <td class="text-center">
                                    <label class="badge {{ $dept->status == 1 ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                        {{ $dept->status == 1 ? 'ACTIVE' : 'INACTIVE' }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-gradient-warning">Edit</a>
                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger" onclick="return confirm('Delete department?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
