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
                            <option value="active" {{ $department->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $department->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
