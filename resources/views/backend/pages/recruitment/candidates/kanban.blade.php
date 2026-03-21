@extends('backend.layouts.app')

@section('title', 'Candidate Pipeline (Kanban) - Recruitment')

@push('styles')
<style>
    .kanban-container {
        display: flex;
        overflow-x: auto;
        padding: 20px 0;
        gap: 20px;
        min-height: calc(100vh - 200px);
    }
    .kanban-column {
        flex: 0 0 300px;
        background: #f4f7fa;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .kanban-header {
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kanban-header h5 {
        margin: 0;
        font-weight: 700;
        color: #3e4b5b;
    }
    .kanban-list {
        flex-grow: 1;
        min-height: 50px;
    }
    .kanban-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        cursor: grab;
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }
    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .kanban-card-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 5px;
        color: #1bcfb4;
    }
    .kanban-card-info {
        font-size: 12px;
        color: #6c7293;
    }
    .kanban-card-job {
        font-size: 11px;
        background: #f0f3ff;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        margin-top: 5px;
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #e0e0e0;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-view-quilt"></i>                 
        </span>
        Candidate Pipeline
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recruitment.candidates.index') }}">Candidates</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kanban</li>
        </ul>
    </nav>
</div>

<div class="kanban-container">
    @foreach($stages as $stage)
    <div class="kanban-column" data-stage-id="{{ $stage->id }}">
        <div class="kanban-header">
            <h5>{{ $stage->name }}</h5>
            <span class="badge badge-pill badge-primary">{{ $stage->candidates->count() }}</span>
        </div>
        <div class="kanban-list" id="stage-{{ $stage->id }}">
            @foreach($stage->candidates as $candidate)
            <div class="kanban-card" data-candidate-id="{{ $candidate->id }}">
                <div class="kanban-card-title">
                    <a href="{{ route('recruitment.candidates.show', $candidate->id) }}" class="text-decoration-none">
                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                    </a>
                </div>
                <div class="kanban-card-info">
                    <i class="mdi mdi-email-outline mr-1"></i> {{ $candidate->email }}
                </div>
                <div class="kanban-card-job text-primary">
                    <i class="mdi mdi-briefcase-outline mr-1"></i> {{ $candidate->jobPost->title }}
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <small class="text-muted">{{ $candidate->updated_at->diffForHumans() }}</small>
                    <div class="dropdown">
                        <i class="mdi mdi-dots-vertical" id="dropdown-{{ $candidate->id }}" data-toggle="dropdown" style="cursor: pointer;"></i>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('recruitment.candidates.edit', $candidate->id) }}">Edit</a>
                            <a class="dropdown-item" href="{{ route('recruitment.interviews.create', ['candidate_id' => $candidate->id]) }}">Schedule Interview</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = document.querySelectorAll('.kanban-list');
        
        columns.forEach(column => {
            new Sortable(column, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    const candidateId = evt.item.getAttribute('data-candidate-id');
                    const newStageId = evt.to.closest('.kanban-column').getAttribute('data-stage-id');
                    const oldStageId = evt.from.closest('.kanban-column').getAttribute('data-stage-id');

                    if (newStageId !== oldStageId) {
                        updateCandidateStage(candidateId, newStageId);
                    }
                }
            });
        });

        function updateCandidateStage(candidateId, stageId) {
            fetch(`/admin/recruitment/candidates/${candidateId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    candidate_stage_id: stageId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Optional: Show toast notification
                    console.log(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update stage. Please refresh.');
            });
        }
    });
</script>
@endpush
