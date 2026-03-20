@extends('backend.layouts.app')

@section('title', 'Employee Salary List - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-account-card-details"></i>                 
        </span> 
        Employee Salary Management
    </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Employee</th>
                                <th>Designation</th>
                                <th>Gross Salary</th>
                                <th>Net Payable</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="py-1">
                                    <div class="d-flex align-items-center">
                                        @if($user->image)
                                            <img src="{{ asset('storage/' . $user->image) }}" class="mr-2" alt="image">
                                        @endif
                                        <span class="font-weight-bold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->designation->name ?? 'N/A' }}</td>
                                <td class="font-weight-bold">
                                    {{ $user->salarySetup ? number_format($user->salarySetup->gross_monthly, 2) : '0.00' }}
                                </td>
                                <td class="text-success font-weight-bold">
                                    {{ $user->salarySetup ? number_format($user->salarySetup->net_monthly, 2) : '0.00' }}
                                </td>
                                <td>{{ $user->salarySetup->currency ?? 'BDT' }}</td>
                                <td>
                                    @if($user->salarySetup)
                                        <label class="badge badge-gradient-success">Setup Done</label>
                                    @else
                                        <label class="badge badge-gradient-danger">Not Configured</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('employee-salary.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-settings"></i> Setup Salary
                                    </a>
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
