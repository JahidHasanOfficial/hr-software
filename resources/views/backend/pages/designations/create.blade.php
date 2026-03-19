@extends('backend.layouts.app')

@section('title', 'Add New Designation - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-tie"></i>                 
        </span>
        Add Designation
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('designations.index') }}">Designations</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Designation Creation</h4>
                
                <form class="forms-sample" action="{{ route('designations.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="department_id">Select Department</label>
                        <select class="form-control" name="department_id" id="department_id" required>
                            <option value="">Choose Dept...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->branch->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Designation Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name (e.g., Software Engineer)" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Job Responsibilities"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Create Designation</button>
                    <a href="{{ route('designations.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
