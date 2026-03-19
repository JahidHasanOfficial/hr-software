@extends('backend.layouts.app')

@section('title', 'Edit Role - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-shield-edit"></i>                 
        </span>
        Edit Role
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $role->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Role Management</h4>
                <p class="card-description">Modify permissions for the {{ $role->name }} role.</p>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form class="forms-sample" action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Role Name" value="{{ old('name', $role->name) }}" required>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="mb-3">Update Permissions</h5>
                        <div class="row">
                            @foreach($permissions as $perm)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check form-check-flat form-check-primary">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                                class="form-check-input" {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}>
                                            {{ ucwords($perm->name) }}
                                        <i class="input-helper"></i></label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary mr-2">Update Role</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
