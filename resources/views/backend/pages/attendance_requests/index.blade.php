@extends('backend.layouts.app')

@section('title', 'Attendance Requests')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-message-bulleted"></i>                 
        </span>
        Regularization & OD Requests
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Requests</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pending & Past Approval Status</h4>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Request Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->user->name }}</td>
                                <td>{{ \App\Services\HelperService::formatDate($req->date) }}</td>
                                <td>
                                    <span class="badge {{ $req->type == 1 ? 'badge-info' : 'badge-primary' }}">
                                        {{ $req->type == 1 ? 'Regularization' : 'On-Duty/Tour' }}
                                    </span>
                                </td>
                                <td>
                                    @if($req->type == 1)
                                        IN: {{ \App\Services\HelperService::formatTime($req->check_in_time) }} 
                                        OUT: {{ \App\Services\HelperService::formatTime($req->check_out_time) }}
                                    @endif
                                    <br>
                                    <small>{{ $req->reason }}</small>
                                </td>
                                <td>
                                    @php
                                        $badge = 'badge-warning'; $text = 'PENDING';
                                        if($req->status == 1) { $badge = 'badge-success'; $text = 'APPROVED'; }
                                        elseif($req->status == 2) { $badge = 'badge-danger'; $text = 'REJECTED'; }
                                    @endphp
                                    <label class="badge {{ $badge }}">{{ $text }}</label>
                                </td>
                                <td>
                                    @if($req->status == 0)
                                        <button class="btn btn-gradient-success btn-xs">Approve</button>
                                        <button class="btn btn-gradient-danger btn-xs">Reject</button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
