@extends('backend.layouts.app')

@section('title', 'Edit Leave Type - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white mr-2">
            <i class="mdi mdi-pencil"></i>
        </span> 
        Edit Leave Type
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('leave-types.index') }}">Leave Types</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Modify Category: {{ $leaveType->name }}</h4>
                
                @include('backend.components.session_alerts')

                <form method="POST" action="{{ route('leave-types.update', $leaveType->id) }}" class="forms-sample row">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group col-md-6">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $leaveType->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $leaveType->code) }}" required>
                        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="quota">Yearly Quota <span class="text-danger">*</span></label>
                        <input type="number" name="quota" id="quota" class="form-control" value="{{ old('quota', $leaveType->quota) }}" required min="0">
                        @error('quota') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6 mt-2">
                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                                <input type="checkbox" name="is_accruable" value="1" {{ old('is_accruable', $leaveType->is_accruable) ? 'checked' : '' }} class="form-check-input">
                                Is Accruable (Gained over time) <i class="input-helper"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-6 mt-2">
                        <div class="form-check form-check-flat form-check-warning">
                            <label class="form-check-label">
                                <input type="checkbox" name="requires_attachment" value="1" {{ old('requires_attachment', $leaveType->requires_attachment) ? 'checked' : '' }} class="form-check-input">
                                Needs Document Attachment <i class="input-helper"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" {{ old('status', $leaveType->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $leaveType->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-gradient-info mr-2">Update Type</button>
                        <a href="{{ route('leave-types.index') }}" class="btn btn-light">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
