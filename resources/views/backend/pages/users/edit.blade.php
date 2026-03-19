@extends('backend.layouts.app')

@section('title', 'Edit User - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-edit"></i>                 
        </span>
        Edit User
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $user->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Update Form</h4>
                <p class="card-description">Modify information for {{ $user->name }}.</p>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form class="forms-sample" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Full Name" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <input type="text" class="form-control" name="designation" id="designation" placeholder="Job Title" value="{{ old('designation', $user->designation) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="joining_date">Joining Date</label>
                                <input type="date" class="form-control" name="joining_date" id="joining_date" value="{{ old('joining_date', $user->joining_date) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                <input type="number" step="0.01" class="form-control" name="salary" id="salary" placeholder="0.00" value="{{ old('salary', $user->salary) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Account Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roles">Assign Roles</label>
                                <select class="form-control select2" multiple="multiple" name="roles[]" id="roles" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Profile Image (Leave blank to keep current)</label>
                        @if($user->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $user->image) }}" alt="user image" style="width: 100px; height: 100px; border-radius: 10px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control file-upload-info" placeholder="Update Image">
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
