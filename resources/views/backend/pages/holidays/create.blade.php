@extends('backend.layouts.app')

@section('title', 'Add New Holiday - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-plus"></i>                 
        </span>
        Add Holiday
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holidays</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Setup Company Holiday</h4>
                
                <form class="forms-sample" action="{{ route('holidays.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            @if(Auth::user()->company_id)
                                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                            @else
                                <div class="form-group">
                                    <label for="company_id">Company</label>
                                    <select class="form-control" name="company_id" id="company_id" required>
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            
                            <div class="form-group">
                                <label for="name">Holiday Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="e.g. Eid-ul-Fitr" required value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Holiday Date</label>
                                <input type="date" class="form-control" name="date" id="date" required value="{{ old('date') }}">
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description (Optional)</label>
                                <textarea class="form-control" name="description" id="description" rows="4">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_recurring" value="1"> 
                                    Recurring Holiday (Every year on this date)
                                <i class="input-helper"></i></label>
                            </div>
                            <input type="hidden" name="is_recurring_val" id="is_recurring_val" value="0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2">Create Holiday</button>
                    <a href="{{ route('holidays.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle checkbox for hidden field if needed, but Laravel usually handles null as 0 if we use a boolean column
    // However, to be safe:
    document.querySelector('form').onsubmit = function() {
        if(!document.querySelector('input[name="is_recurring"]').checked) {
            // Add a hidden field with value 0 if not checked
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'is_recurring';
            input.value = '0';
            this.appendChild(input);
        }
    };
</script>
@endsection
