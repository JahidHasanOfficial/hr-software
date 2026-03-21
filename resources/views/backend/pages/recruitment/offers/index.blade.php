@extends('backend.layouts.app')

@section('title', 'Offers - Recruitment')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-email"></i>                 
        </span>
        Job Offers
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recruitment</li>
            <li class="breadcrumb-item active" aria-current="page">Offers</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h4 class="card-title mb-0">Offer List</h4>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                        <a href="{{ route('recruitment.offers.create') }}" class="btn btn-gradient-primary btn-sm btn-fw ml-2">Generate New Offer</a>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Candidate</th>
                                <th>Offered Salary</th>
                                <th>Joining Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offers as $offer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($offer->candidate->first_name . ' ' . $offer->candidate->last_name) }}&size=30&background=random" class="mr-2 rounded-circle" alt="image">
                                        {{ $offer->candidate->first_name }} {{ $offer->candidate->last_name }}
                                    </div>
                                </td>
                                <td><span class="text-success">{{ number_format($offer->offered_salary) }} BDT</span></td>
                                <td>{{ $offer->joining_date ? \Carbon\Carbon::parse($offer->joining_date)->format('M d, Y') : 'Not set' }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'sent' => 'primary',
                                            'accepted' => 'success',
                                            'declined' => 'danger',
                                            'expired' => 'secondary'
                                        ][$offer->status] ?? 'secondary';
                                    @endphp
                                    <label class="badge badge-{{ $statusClass }} text-white">{{ ucfirst($offer->status) }}</label>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('recruitment.offers.edit', $offer->id) }}" class="btn btn-sm btn-gradient-warning p-2 mr-1" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('recruitment.offers.destroy', $offer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger p-2" title="Delete" onclick="return confirm('Are you sure?')"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No offers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $offers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
