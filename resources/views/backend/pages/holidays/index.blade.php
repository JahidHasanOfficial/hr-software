@extends('backend.layouts.app')

@section('title', 'Holiday Management')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar"></i>                 
        </span>
        Holidays List
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Holidays</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Company Holidays</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        @include('backend.components.search_box', ['action' => route('holidays.index'), 'placeholder' => 'Search holidays...'])
                        <a href="{{ route('holidays.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">
                            <i class="mdi mdi-plus"></i> Add Holiday
                        </a>
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
                                <th>Company</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Recurring</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidays as $holiday)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $holiday->company->name ?? 'N/A' }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>{{ \App\Services\HelperService::formatDate($holiday->date) }}</td>
                                <td>
                                    <label class="badge {{ $holiday->is_recurring ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $holiday->is_recurring ? 'YEARLY' : 'ONCE' }}
                                    </label>
                                </td>
                                <td>
                                    {!! \App\Services\HelperService::getStatusBadge($holiday->status) !!}
                                </td>
                                <td>
                                      <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-sm btn-gradient-warning p-2" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Are you sure you want to delete this holiday?')"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
