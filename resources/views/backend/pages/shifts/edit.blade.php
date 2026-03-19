@extends('backend.layouts.app')

@section('title', 'Edit Shift - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-clock-outline"></i>                 
        </span>
        Edit Shift
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">Shifts</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $shift->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Shift Details</h4>
                
                <form class="forms-sample" action="{{ route('shifts.update', $shift->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Shift Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $shift->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="late_threshold">Late Mark Threshold (Minutes)</label>
                                <input type="number" class="form-control" name="late_threshold" id="late_threshold" value="{{ old('late_threshold', $shift->late_threshold) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_time">Start Time</label>
                                <input type="time" class="form-control" name="start_time" id="start_time" value="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" class="form-control" name="end_time" id="end_time" value="{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="active" {{ $shift->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $shift->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_flexible" value="1" {{ $shift->is_flexible ? 'checked' : '' }}> 
                                    Flexible Shift
                                <i class="input-helper"></i></label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Shift</button>
                    <a href="{{ route('shifts.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
