@extends('backend.layouts.app')

@section('title', 'Leave Types - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-settings"></i>
        </span> 
        Leave Types
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Types</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Manage Leave Categories</h4>
                    @can('leave_type.create')
                    <a href="{{ route('leave-types.create') }}" class="btn btn-gradient-primary btn-sm btn-icon-text">
                        <i class="mdi mdi-plus btn-icon-prepend"></i> Add Type
                    </a>
                    @endcan
                </div>

                @include('backend.components.session_alerts')

                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Name </th>
                                <th> Code </th>
                                <th> Yearly Quota </th>
                                <th> Details </th>
                                <th> Status </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveTypes as $type)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td class="font-weight-bold text-dark"> {{ $type->name }} </td>
                                <td> <label class="badge badge-inverse-primary font-weight-bold">{{ $type->code }}</label> </td>
                                <td> {{ $type->quota }} Days </td>
                                <td class="small">
                                    @if($type->is_accruable)
                                        <i class="mdi mdi-chart-line text-info"></i> Accruable<br>
                                    @endif
                                    @if($type->requires_attachment)
                                        <i class="mdi mdi-attachment text-warning"></i> Needs Doc
                                    @endif
                                </td>
                                <td> {!! \App\Services\HelperService::getStatusBadge($type->status) !!} </td>
                                <td>
                                    @can('leave_type.edit')
                                    <a href="{{ route('leave-types.edit', $type->id) }}" class="btn btn-inverse-info btn-xs" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('leave_type.destroy')
                                    <form action="{{ route('leave-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this leave type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-inverse-danger btn-xs" title="Delete">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-4">No leave types found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $leaveTypes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
