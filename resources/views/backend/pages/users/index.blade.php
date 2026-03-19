@extends('backend.layouts.app')

@section('title', 'Manage Users - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-multiple"></i>                 
        </span>
        Manage Users
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">User List</h4>
                    <a href="{{ route('users.create') }}" class="btn btn-gradient-primary btn-fw">Add User</a>
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
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Designation</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="py-1">
                                    @if($user->image)
                                        <img src="{{ asset('storage/' . $user->image) }}" alt="image" style="width: 40px; height: 40px; border-radius: 50%;">
                                    @else
                                        <img src="{{ asset('backend/images/faces/face1.jpg') }}" alt="image" style="width: 40px; height: 40px; border-radius: 50%;">
                                    @endif
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>{{ $user->designation ?? 'N/A' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <label class="badge badge-gradient-info text-dark">{{ $role->name }}</label>
                                    @endforeach
                                </td>
                                <td>
                                    <label class="badge {{ $user->status == 'active' ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-gradient-info mb-1">View</a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-gradient-warning mb-1">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
