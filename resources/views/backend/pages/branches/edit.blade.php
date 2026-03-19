@extends('backend.layouts.app')

@section('title', 'Edit Branch - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-map-marker-edit"></i>                 
        </span>
        Edit Branch
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $branch->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Branch Details</h4>
                
                <form class="forms-sample" action="{{ route('branches.update', $branch->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_id">Select Company</label>
                                <select class="form-control" name="company_id" id="company_id" required>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $branch->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_id">Branch Default Shift</label>
                                <select class="form-control" name="shift_id" id="shift_id">
                                    <option value="">No Default Shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ $branch->shift_id == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }} ({{ \App\Services\HelperService::formatTime($shift->start_time) }} - {{ \App\Services\HelperService::formatTime($shift->end_time) }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Changes here will apply to all employees in this branch who don't have override shifts.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Branch Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', $branch->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location / Address</label>
                        <input type="text" class="form-control" name="location" id="location" placeholder="City" value="{{ old('location', $branch->location) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Branch Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email', $branch->email) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Branch Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ old('phone', $branch->phone) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="1" {{ $branch->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $branch->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Branch</button>
                    <a href="{{ route('branches.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
