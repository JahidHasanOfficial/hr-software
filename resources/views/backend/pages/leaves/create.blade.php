@extends('backend.layouts.app')

@section('title', 'Apply for Leave - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-plus"></i>
        </span> 
        Apply for Leave
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Apply</li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Request New Leave</h4>
                
                @include('backend.components.session_alerts')

                <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data" class="forms-sample row">
                    @csrf
                    
                    <div class="form-group col-md-6">
                        <label for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }} data-requires-attachment="{{ $type->requires_attachment }}">
                                {{ $type->name }} ({{ $type->quota }} days quota)
                            </option>
                            @endforeach
                        </select>
                        @error('leave_type_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="day_type">Duration Type <span class="text-danger">*</span></label>
                        <select name="day_type" id="day_type" class="form-control" onchange="toggleDateRange()" required>
                            <option value="full" {{ old('day_type') == 'full' ? 'selected' : '' }}>Full Day</option>
                            <option value="first_half" {{ old('day_type') == 'first_half' ? 'selected' : '' }}>First Half (Half Day)</option>
                            <option value="second_half" {{ old('day_type') == 'second_half' ? 'selected' : '' }}>Second Half (Half Day)</option>
                        </select>
                        @error('day_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required onchange="calculateDays()">
                        @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6" id="end_date_group">
                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d')) }}" onchange="calculateDays()">
                        @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Total Days calculated: <span id="days_display" class="font-weight-bold ml-2">1</span></label>
                        <input type="hidden" name="total_days" id="total_days" value="1">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="reason">Reason for Leave <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="4" class="form-control" placeholder="Provide a detailed reason..." required>{{ old('reason') }}</textarea>
                        @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-12" id="attachment_group" style="display:none;">
                        <label for="attachment">Attachment (Required for this Leave Type)</label>
                        <input type="file" name="attachment" id="attachment" class="form-control">
                        <small class="text-muted">PDF, JPG, or PNG (Max 2MB)</small>
                        @error('attachment') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-gradient-primary btn-md mr-2">Submit Request</button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        toggleDateRange();
        toggleAttachmentRequirement();

        document.getElementById('leave_type_id').addEventListener('change', toggleAttachmentRequirement);
    });

    function toggleDateRange() {
        const dayType = document.getElementById('day_type').value;
        const endDateGroup = document.getElementById('end_date_group');
        
        if (dayType === 'full') {
            endDateGroup.style.display = 'block';
        } else {
            endDateGroup.style.display = 'none';
        }
        calculateDays();
    }

    function toggleAttachmentRequirement() {
        const leaveTypeSelect = document.getElementById('leave_type_id');
        const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
        const isRequired = selectedOption ? selectedOption.getAttribute('data-requires-attachment') === '1' : false;
        
        document.getElementById('attachment_group').style.display = isRequired ? 'block' : 'none';
    }

    function calculateDays() {
        const dayType = document.getElementById('day_type').value;
        const startDateValue = document.getElementById('start_date').value;
        const endDateValue = document.getElementById('end_date').value;
        
        let total = 0;

        if (dayType === 'full') {
            if (startDateValue && endDateValue) {
                const start = new Date(startDateValue);
                const end = new Date(endDateValue);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                total = (end >= start) ? diffDays : 0;
            }
        } else {
            total = 0.5;
        }

        document.getElementById('days_display').textContent = total + ' Days';
        document.getElementById('total_days').value = total;
    }
</script>
@endpush
@endsection
