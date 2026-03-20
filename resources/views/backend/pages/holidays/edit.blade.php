@extends('backend.layouts.app')

@section('title', 'Edit Holiday - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-edit"></i>                 
        </span>
        Edit Holiday
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holidays</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Update Holiday Details</h4>
                
                <form class="forms-sample" action="{{ route('holidays.update', $holiday->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            @if(Auth::user()->company_id)
                                <input type="hidden" name="company_id" value="{{ $holiday->company_id }}">
                            @else
                                <div class="form-group">
                                    <label for="company_id">Company</label>
                                    <select class="form-control" name="company_id" id="company_id" required>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ $holiday->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            
                            <div class="form-group">
                                <label for="name">Holiday Name</label>
                                <input type="text" class="form-control" name="name" id="name" required value="{{ old('name', $holiday->name) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Holiday Date</label>
                                <input type="date" class="form-control" name="date" id="date" required value="{{ old('date', $holiday->date) }}">
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1" {{ $holiday->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $holiday->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description (Optional)</label>
                                <textarea class="form-control" name="description" id="description" rows="4">{{ old('description', $holiday->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_recurring" value="1" {{ $holiday->is_recurring ? 'checked' : '' }}> 
                                    Recurring Holiday (Every year on this date)
                                <i class="input-helper"></i></label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Holiday</button>
                    <a href="{{ route('holidays.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
