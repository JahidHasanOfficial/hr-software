<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateStage;
use Illuminate\Support\Facades\DB;

class RecruitmentService
{
    /**
     * Get candidate data grouped by stages for Kanban board.
     */
    public function getKanbanData()
    {
        return CandidateStage::with(['candidates' => function($query) {
            $query->with('jobPost')->orderBy('updated_at', 'desc');
        }])->orderBy('order')->get();
    }

    /**
     * Move a candidate to a different stage.
     */
    public function moveCandidate($candidateId, $stageId)
    {
        return DB::transaction(function() use ($candidateId, $stageId) {
            $candidate = Candidate::findOrFail($candidateId);
            $candidate->update([
                'candidate_stage_id' => $stageId,
                'updated_at' => now()
            ]);
            
            // You can add logic here to trigger notifications, 
            // auto-schedule screening, etc.

            return $candidate;
        });
    }

    /**
     * Basic check for duplicate candidates based on email.
     */
    public function isDuplicate($email, $jobPostId)
    {
        return Candidate::where('email', $email)
            ->where('job_post_id', $jobPostId)
            ->exists();
    }
}
