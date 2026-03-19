@extends('backend.layouts.app')

@section('title', 'Edit Designation - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-tie"></i>                 
        </span>
        Edit Designation
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('designations.index') }}">Designations</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $designation->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Position Details</h4>
                
                <form class="forms-sample" action="{{ route('designations.update', $designation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="department_id">Select Department</label>
                        <select class="form-control" name="department_id" id="department_id" required>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $designation->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->branch->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Designation Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', $designation->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ old('description', $designation->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="active" {{ $designation->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $designation->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Designation</button>
                    <a href="{{ route('designations.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
