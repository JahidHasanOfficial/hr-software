@extends('backend.layouts.app')

@section('title', 'Role Details - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-shield-check"></i>                 
        </span>
        Role Details
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $role->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Role: {{ $role->name }}</h4>
                <div class="mt-4">
                    <p><strong>Guard Name:</strong> {{ $role->guard_name }}</p>
                    <p><strong>Permissions:</strong></p>
                    <div class="row">
                        @foreach($role->permissions as $perm)
                            <div class="col-md-3 mb-2">
                                <label class="badge badge-gradient-info text-dark w-100">{{ $perm->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-gradient-warning">Edit Role</a>
                    <a href="{{ route('roles.index') }}" class="btn btn-light">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
