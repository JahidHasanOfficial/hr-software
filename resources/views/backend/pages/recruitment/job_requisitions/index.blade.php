@extends('backend.layouts.app')

@section('title', 'Job Requisitions - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-search"></i>                 
        </span>
        Job Requisitions
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recruitment</li>
            <li class="breadcrumb-item active" aria-current="page">Requisitions</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Requisition List</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        <a href="{{ route('recruitment.job-requisitions.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">Create Requisition</a>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Job Title</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Headcount</th>
                                <th>Urgency</th>
                                <th>Requested By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requisitions as $requisition)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $requisition->title }}</td>
                                <td>{{ $requisition->department->name }}</td>
                                <td>{{ $requisition->designation->name }}</td>
                                <td>{{ $requisition->headcount }}</td>
                                <td>
                                    @php
                                        $badgeColor = [
                                            'low' => 'info',
                                            'medium' => 'primary',
                                            'high' => 'warning',
                                            'critical' => 'danger'
                                        ][$requisition->urgency_level] ?? 'secondary';
                                    @endphp
                                    <label class="badge badge-gradient-{{ $badgeColor }}">{{ ucfirst($requisition->urgency_level) }}</label>
                                </td>
                                <td>{{ $requisition->requester->name }}</td>
                                <td>
                                    @php
                                        $statusColor = [
                                            'draft' => 'secondary',
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'on_hold' => 'info',
                                            'filled' => 'dark'
                                        ][$requisition->status] ?? 'light';
                                    @endphp
                                    <label class="badge badge-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $requisition->status)) }}</label>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('recruitment.job-requisitions.show', $requisition->id) }}" class="btn btn-sm btn-gradient-info p-2 mr-1" title="View"><i class="mdi mdi-eye"></i></a>
                                        @if($requisition->status == 'pending')
                                            <a href="{{ route('recruitment.job-requisitions.edit', $requisition->id) }}" class="btn btn-sm btn-gradient-warning p-2 mr-1" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        @endif
                                        <form action="{{ route('recruitment.job-requisitions.destroy', $requisition->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Are you sure?')"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No requisitions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $requisitions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
