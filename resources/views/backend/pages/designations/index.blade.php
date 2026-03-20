@extends('backend.layouts.app')

@section('title', 'Manage Designations - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-tie"></i>                 
        </span>
        Organization: Designations
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Designations</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Designation List</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        @include('backend.components.search_box', ['action' => route('designations.index'), 'placeholder' => 'Search designation...'])
                        <a href="{{ route('designations.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">Add Pos</a>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dept > Branch</th>
                                <th>Position Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designations as $desig)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $desig->department->name }} ({{ $desig->department->branch->name }})</td>
                                <td>{{ $desig->name }}</td>
                                <td>
                                    {!! \App\Services\HelperService::getStatusBadge($desig->status) !!}
                                </td>
                                <td>
                                    <a href="{{ route('designations.edit', $desig->id) }}" class="btn btn-sm btn-gradient-warning p-2" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                    <form action="{{ route('designations.destroy', $desig->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Delete designation?')"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $designations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
