@extends('backend.layouts.app')

@section('title', 'Manage Branches - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-map-marker-radius"></i>                 
        </span>
        Organization: Branches
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Branches</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Branch List</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        @include('backend.components.search_box', ['action' => route('branches.index'), 'placeholder' => 'Search branch...'])
                        <a href="{{ route('branches.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">Add Branch</a>
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
                                <th>Company</th>
                                <th>Branch Name</th>
                                <th>Location</th>
                                <th>Email/Phone</th>
                                <th>Default Shift</th>
                                <th class="text-center">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $branch->company->name ?? '-' }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->location ?? 'N/A' }}</td>
                                <td>{{ $branch->email }} <br> {{ $branch->phone }}</td>
                                <td>
                                    {{ $branch->shift ? $branch->shift->name : 'NONE' }}
                                </td>
                                <td class="text-center">
                                    {!! \App\Services\HelperService::getStatusBadge($branch->status) !!}
                                </td>
                                <td>
                                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-gradient-warning p-2" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Delete branch?')"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $branches->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
