@extends('backend.layouts.app')

@section('title', 'Salary Components - Purple Admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-cash-multiple"></i>                 
        </span> 
        Salary Components
    </h3>
    <button type="button" class="btn btn-gradient-primary btn-sm" data-toggle="modal" data-target="#addComponent">
        <i class="mdi mdi-plus btn-icon-prepend"></i> Add Component
    </button>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Unit</th>
                                <th>Statutory</th>
                                <th>Taxable</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($components as $comp)
                            <tr>
                                <td class="font-weight-bold">{{ $comp->name }}</td>
                                <td>
                                    <span class="badge {{ $comp->type == 'earning' ? 'badge-success' : ($comp->type == 'deduction' ? 'badge-danger' : 'badge-info') }}">
                                        {{ ucfirst($comp->type) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($comp->unit) }}</td>
                                <td>
                                    <i class="mdi {{ $comp->is_statutory ? 'mdi-check-circle text-success' : 'mdi-close-circle text-muted' }}"></i>
                                </td>
                                <td>
                                    <i class="mdi {{ $comp->is_taxable ? 'mdi-check-circle text-success' : 'mdi-close-circle text-muted' }}"></i>
                                </td>
                                <td>
                                    <label class="badge {{ $comp->status ? 'badge-gradient-success' : 'badge-gradient-secondary' }}">
                                        {{ $comp->status ? 'Active' : 'Inactive' }}
                                    </label>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editModal{{ $comp->id }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <form action="{{ route('salary-components.destroy', $comp->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this component?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $comp->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content text-left">
                                        <div class="modal-header bg-gradient-primary text-white">
                                            <h5 class="modal-title">Edit Component: {{ $comp->name }}</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('salary-components.update', $comp->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Component Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" value="{{ $comp->name }}" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Type <span class="text-danger">*</span></label>
                                                    <select name="type" class="form-control" required>
                                                        <option value="earning" {{ $comp->type == 'earning' ? 'selected' : '' }}>Earning (Allowance)</option>
                                                        <option value="deduction" {{ $comp->type == 'deduction' ? 'selected' : '' }}>Deduction</option>
                                                        <option value="reimbursement" {{ $comp->type == 'reimbursement' ? 'selected' : '' }}>Reimbursement</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Unit <span class="text-danger">*</span></label>
                                                    <select name="unit" class="form-control" required>
                                                        <option value="fixed" {{ $comp->unit == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                        <option value="percentage" {{ $comp->unit == 'percentage' ? 'selected' : '' }}>Percentage (of Basic)</option>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-check form-check-flat form-check-primary mt-0">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="is_statutory" class="form-check-input" value="1" {{ $comp->is_statutory ? 'checked' : '' }}> Statutory?
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-check form-check-flat form-check-primary mt-0">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="is_taxable" class="form-check-input" value="1" {{ $comp->is_taxable ? 'checked' : '' }}> Taxable?
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mt-0 pt-0">
                                                            <select name="status" class="form-control form-control-sm">
                                                                <option value="1" {{ $comp->status == 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ $comp->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-gradient-primary">Update Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addComponent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">New Salary Component</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('salary-components.store') }}" method="POST">
                @csrf
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label>Component Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Special Allowance" required>
                    </div>
                    <div class="form-group">
                        <label>Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="earning">Earning (Allowance)</option>
                            <option value="deduction">Deduction</option>
                            <option value="reimbursement">Reimbursement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unit <span class="text-danger">*</span></label>
                        <select name="unit" class="form-control" required>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage (of Basic)</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" name="is_statutory" class="form-check-input" value="1"> Is Statutory?
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="checkbox" name="is_taxable" class="form-check-input" value="1" checked> Is Taxable?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient-primary">Save Component</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
