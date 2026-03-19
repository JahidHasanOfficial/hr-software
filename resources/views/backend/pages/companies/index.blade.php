@extends('backend.layouts.app')

@section('title', 'Manage Companies - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-office-building"></i>                 
        </span>
        Organization Management
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Companies</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Company List</h4>
                    <a href="{{ route('companies.create') }}" class="btn btn-gradient-primary btn-fw">Add Company</a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Website</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                            <tr>
                                <td class="py-1">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="logo" style="width: 40px; height: 40px; border-radius: 5px;">
                                    @else
                                        <img src="{{ asset('backend/images/dashboard/circle.svg') }}" alt="logo" style="width: 40px; height: 40px; border-radius: 5px;">
                                    @endif
                                </td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->email }}</td>
                                <td>{{ $company->phone ?? 'N/A' }}</td>
                                <td>{{ $company->website ?? 'N/A' }}</td>
                                <td>
                                    <label class="badge {{ $company->status == 'active' ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                        {{ strtoupper($company->status) }}
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm btn-gradient-info">View</a>
                                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-gradient-warning">Edit</a>
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger" onclick="return confirm('Delete company and all its data?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
