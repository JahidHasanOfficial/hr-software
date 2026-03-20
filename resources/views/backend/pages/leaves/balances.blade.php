@extends('backend.layouts.app')

@section('title', 'Employee Leave Balances - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-scale-balance"></i>
        </span> 
        Employee Leave Balances
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Balances</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Detailed Leave Balances ({{ date('Y') }})</h4>
                </div>

                @include('backend.components.session_alerts')

                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead class="text-primary font-weight-bold">
                            <tr>
                                <th> Employee </th>
                                <th> Leave Type </th>
                                <th> Total Quota </th>
                                <th> Used </th>
                                <th> Remaining </th>
                                <th> Year </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                            <tr>
                                <td class="text-left py-3">
                                    <div class="d-flex align-items-center">
                                        @if($balance->user->image)
                                            <img src="{{ asset('storage/' . $balance->user->image) }}" class="mr-3" alt="image">
                                        @endif
                                        <div>
                                            <p class="mb-0 font-weight-bold">{{ $balance->user->name }}</p>
                                            <small class="text-muted">{{ $balance->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td> <label class="badge badge-outline-secondary">{{ $balance->leaveType->name }}</label> </td>
                                <td class="font-weight-bold text-dark"> {{ $balance->total_quota }} </td>
                                <td class="text-danger font-weight-bold"> {{ $balance->used_quota }} </td>
                                <td class="text-success font-weight-bold h5"> {{ $balance->remaining_quota }} </td>
                                <td> {{ $balance->year }} </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center">No leave balances initialized yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $balances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
