@extends('backend.layouts.app')

@section('title', 'Add New Branch - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-map-marker-plus"></i>                 
        </span>
        Add Branch
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Branch Creation</h4>
                <p class="card-description">Add a physical or digital branch under a company.</p>
                
                <form class="forms-sample" action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_id">Select Company</label>
                                <select class="form-control" name="company_id" id="company_id" required>
                                    <option value="">Choose Company...</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
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
                                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{ \App\Services\HelperService::formatTime($shift->start_time) }} - {{ \App\Services\HelperService::formatTime($shift->end_time) }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Employees in this branch will follow this shift unless they have a department or personal shift.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Branch Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Branch Name" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location / Address</label>
                        <input type="text" class="form-control" name="location" id="location" placeholder="City or Full Address">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Branch Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Branch Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Create Branch</button>
                    <a href="{{ route('branches.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
