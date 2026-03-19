@extends('backend.layouts.app')

@section('title', 'Add New Company - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-office-building"></i>                 
        </span>
        Add Company
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Company Registration</h4>
                <p class="card-description">Create a new company entity for the SaaS HR system.</p>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form class="forms-sample" action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Company Name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Company Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website">Website URL (http://...)</label>
                                <input type="url" class="form-control" name="website" id="website" placeholder="Website" value="{{ old('website') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Full Address">{{ old('address') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Logo</label>
                                <input type="file" name="logo" class="form-control file-upload-info" placeholder="Upload Logo">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Create Company</button>
                    <a href="{{ route('companies.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
