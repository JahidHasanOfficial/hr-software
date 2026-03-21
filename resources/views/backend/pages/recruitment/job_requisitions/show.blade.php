@extends('backend.layouts.app')

@section('title', 'Requisition Details - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-information-outline"></i>                 
        </span>
        Requisition Details
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.job-requisitions.index') }}">Job Requisitions</a></li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title text-primary mb-0">General Information</h4>
                    <div>
                        @if($requisition->status == 'pending')
                            <form action="{{ route('recruitment.job-requisitions.approve', $requisition->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm btn-icon-text">
                                    <i class="mdi mdi-check btn-icon-prepend"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('recruitment.job-requisitions.reject', $requisition->id) }}" method="POST" class="d-inline ml-1">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm btn-icon-text">
                                    <i class="mdi mdi-close btn-icon-prepend"></i> Reject
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('recruitment.job-requisitions.index') }}" class="btn btn-light btn-sm ml-2">Back to List</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <table class="table table-borderless table-striped">
                            <tbody>
                                <tr>
                                    <th style="width: 40%">Job Title</th>
                                    <td>{{ $requisition->title }}</td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td>{{ $requisition->department->name }}</td>
                                </tr>
                                <tr>
                                    <th>Designation</th>
                                    <td>{{ $requisition->designation->name }}</td>
                                </tr>
                                <tr>
                                    <th>Headcount</th>
                                    <td><span class="font-weight-bold text-dark">{{ $requisition->headcount }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 mb-4">
                        <table class="table table-borderless table-striped">
                            <tbody>
                                <tr>
                                    <th style="width: 40%">Urgency Level</th>
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
                                </tr>
                                <tr>
                                    <th>Requested By</th>
                                    <td>{{ $requisition->requester->name }}</td>
                                </tr>
                                <tr>
                                    <th>Request Date</th>
                                    <td>{{ $requisition->created_at->format('d M, Y (h:i A)') }}</td>
                                </tr>
                                <tr>
                                    <th>Current Status</th>
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
                                        <label class="badge badge-outline-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $requisition->status)) }}</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    @if($requisition->justification)
                    <div class="col-md-12 mb-4">
                        <h5 class="text-secondary mb-3">Justification & Reason</h5>
                        <div class="p-3 bg-light border border-radius-sm">
                            {!! nl2br(e($requisition->justification)) !!}
                        </div>
                    </div>
                    @endif

                    @if($requisition->budget_details)
                    <div class="col-md-12 mb-4">
                        <h5 class="text-secondary mb-3">Budget Allocation / Details</h5>
                        <div class="p-3 bg-light border border-radius-sm">
                            {!! nl2br(e($requisition->budget_details)) !!}
                        </div>
                    </div>
                    @endif
                </div>

                @if($requisition->status == 'approved')
                <div class="mt-4 border-top pt-4">
                    <h4 class="card-title text-success">Approved for Recruitment</h4>
                    <p class="text-muted">This requisition has been approved. You can now create a public job post from it.</p>
                    <a href="{{ route('recruitment.job-posts.create', ['requisition_id' => $requisition->id]) }}" class="btn btn-gradient-info">
                        <i class="mdi mdi-plus btn-icon-prepend"></i> Create Job Post
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
