@extends('backend.layouts.app')

@section('title', 'Edit Department - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-group"></i>                 
        </span>
        Edit Department
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $department->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Department Details</h4>
                
                <form class="forms-sample" action="{{ route('departments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_id">Select Branch</label>
                                <select class="form-control" name="branch_id" id="branch_id" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $department->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->company->name }} > {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_id">Department Default Shift</label>
                                <select class="form-control" name="shift_id" id="shift_id">
                                    <option value="">No Default Shift (Fails back to Branch)</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ $department->shift_id == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }} ({{ \App\Services\HelperService::formatTime($shift->start_time) }} - {{ \App\Services\HelperService::formatTime($shift->end_time) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Department Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', $department->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ old('description', $department->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="1" {{ $department->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $department->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Department</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
