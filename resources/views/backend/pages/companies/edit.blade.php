@extends('backend.layouts.app')

@section('title', 'Edit Company - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-office-building"></i>                 
        </span>
        Edit Company
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $company->name }}</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modify Company Details</h4>
                <p class="card-description">Update organization information for {{ $company->name }}.</p>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form class="forms-sample" action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', $company->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Company Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email', $company->email) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ old('phone', $company->phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website">Website URL (http://...)</label>
                                <input type="url" class="form-control" name="website" id="website" placeholder="Website" value="{{ old('website', $company->website) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Full Address">{{ old('address', $company->address) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="active" {{ old('status', $company->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $company->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Logo (Leave blank to keep current)</label>
                                @if($company->logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="logo" style="width: 80px; height: 80px; border-radius: 10px;">
                                    </div>
                                @endif
                                <input type="file" name="logo" class="form-control file-upload-info" placeholder="Update Logo">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Update Company</button>
                    <a href="{{ route('companies.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
