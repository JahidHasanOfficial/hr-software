<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'job_post_id',
        'candidate_stage_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'resume_path',
        'expected_salary',
        'experience_years',
        'status',
        'rejection_reason'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function stage()
    {
        return $this->belongsTo(CandidateStage::class, 'candidate_stage_id');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function scorecards()
    {
        return $this->hasMany(Scorecard::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }
}
