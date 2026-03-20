@extends('backend.layouts.app')

@section('title', 'Leave Management - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-text"></i>
        </span> 
        Leave Management
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    @foreach($balances as $balance)
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-{{ $loop->index == 0 ? 'info' : ($loop->index == 1 ? 'success' : 'danger') }} card-img-holder text-white shadow-sm">
            <div class="card-body">
                <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">{{ $balance->leaveType->name }} ({{ $balance->year }})
                    <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ $balance->remaining_quota }} <small>/ {{ $balance->total_quota }} Days</small></h2>
                <h6 class="card-text">Used: {{ $balance->used_quota }} Days</h6>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Leave Applications</h4>
                    @can('leave.create')
                    <a href="{{ route('leaves.create') }}" class="btn btn-gradient-primary btn-sm btn-icon-text">
                        <i class="mdi mdi-plus btn-icon-prepend"></i> Apply for Leave
                    </a>
                    @endcan
                </div>

                @include('backend.components.session_alerts')

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th> Employee </th>
                                <th> Leave Type </th>
                                <th> Duration </th>
                                <th> Total Days </th>
                                <th> Status </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                            <tr class="text-center">
                                <td>
                                    @if($leave->user->image)
                                        <img src="{{ asset('storage/' . $leave->user->image) }}" class="mr-2" alt="image">
                                    @endif
                                    {{ $leave->user->name }}
                                </td>
                                <td> <label class="badge badge-outline-dark">{{ $leave->leaveType->name }} </label></td>
                                <td> 
                                    {{ \App\Services\HelperService::formatDate($leave->start_date) }} 
                                    @if($leave->end_date && $leave->day_type == 'full')
                                         - {{ \App\Services\HelperService::formatDate($leave->end_date) }}
                                    @endif
                                    <br> <small class="text-muted">({{ strtoupper(str_replace('_', ' ', $leave->day_type)) }})</small>
                                </td>
                                <td class="font-weight-bold"> {{ $leave->total_days }} </td>
                                <td>
                                    @if($leave->status == 'pending')
                                        <label class="badge badge-gradient-warning">PENDING</label>
                                    @elseif($leave->status == 'approved')
                                        <label class="badge badge-gradient-success">APPROVED</label>
                                    @else
                                        <label class="badge badge-gradient-danger">REJECTED</label>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->status == 'pending')
                                        @can('leave.approve')
                                        <form action="{{ route('leaves.approve', $leave->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-inverse-success btn-xs" title="Approve">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                        </form>
                                        @endcan
                                        
                                        @can('leave.reject')
                                        <button type="button" class="btn btn-inverse-danger btn-xs" title="Reject" data-toggle="modal" data-target="#rejectModal{{ $leave->id }}">
                                            <i class="mdi mdi-close"></i>
                                        </button>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('leaves.reject', $leave->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Leave Application</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <div class="form-group text-left">
                                                                <label>Reason for Rejection</label>
                                                                <textarea name="rejection_reason" class="form-control" placeholder="Explain why the leave is rejected..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endcan
                                    @else
                                        <span class="text-muted small">Process Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center p-4">No leave applications found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $leaves->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
