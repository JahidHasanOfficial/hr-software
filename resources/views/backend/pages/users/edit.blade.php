@extends('backend.layouts.app')

@section('title', 'Edit Employee - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-edit"></i>                 
        </span>
        Edit Employee
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Employees</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Employee Information: {{ $user->name }}</h4>
                <p class="card-description">Update employee details and assignments</p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form class="forms-sample" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                @if($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" class="img-thumbnail mb-2" style="max-height: 150px;" alt="profile">
                                @else
                                    <img src="{{ asset('backend/images/faces/face1.jpg') }}" class="img-thumbnail mb-2" style="max-height: 150px;" alt="profile">
                                @endif
                                <br>
                                <label for="image">Change Profile Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">Work Assignment</h5>
                            <div class="form-group">
                                <label for="company_id">Company</label>
                                <select class="form-control" name="company_id" id="company_id">
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="branch_id">Branch</label>
                                <select class="form-control" name="branch_id" id="branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }} ({{ $branch->company->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="department_id">Department</label>
                                <select class="form-control" name="department_id" id="department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }} ({{ $dept->branch->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="designation_id">Designation</label>
                                <select class="form-control" name="designation_id" id="designation_id">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $desig)
                                        <option value="{{ $desig->id }}" {{ old('designation_id', $user->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->name }} ({{ $desig->department->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="shift_id">Office Shift</label>
                                <select class="form-control" name="shift_id" id="shift_id">
                                    <option value="">Select Shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('shift_id', $user->shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->name }} ({{ \App\Services\HelperService::formatTime($shift->start_time) }} - {{ \App\Services\HelperService::formatTime($shift->end_time) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">Personal & Security</h5>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="form-group">
                                <label for="password">Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="joining_date">Joining Date</label>
                                <input type="date" class="form-control" name="joining_date" id="joining_date" value="{{ old('joining_date', $user->joining_date) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="salary">Base Salary</label>
                                <input type="number" step="0.01" class="form-control" name="salary" id="salary" value="{{ old('salary', $user->salary) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Account Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roles">System Roles</label>
                                <select class="form-control js-example-basic-multiple" name="roles[]" id="roles" multiple="multiple">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Employee</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
