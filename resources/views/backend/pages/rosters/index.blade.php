@extends('backend.layouts.app')

@section('title', 'Roster / Shift Scheduling')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-clock"></i>                 
        </span>
        Shift Roster List
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roster</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Assigned Shifts by Date</h4>
                    <a href="#" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Assign Shift
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Assigned Shift</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rosters as $roster)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $roster->user->name }}</td>
                                <td>{{ \App\Services\HelperService::formatDate($roster->date) }}</td>
                                <td>
                                    <label class="badge badge-gradient-info text-white">
                                        {{ $roster->shift->name }}
                                    </label>
                                </td>
                                <td>
                                    {{ \App\Services\HelperService::formatTime($roster->shift->start_time) }} - 
                                    {{ \App\Services\HelperService::formatTime($roster->shift->end_time) }}
                                </td>
                                <td>
                                    <form action="{{ route('rosters.destroy', $roster->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Delete company and all its data?')"><i class="mdi mdi-delete"></i></button>
                                    </form>
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
