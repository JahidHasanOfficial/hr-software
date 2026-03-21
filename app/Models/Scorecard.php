<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    protected $fillable = [
        'candidate_id',
        'interview_id',
        'interviewer_id',
        'ratings',
        'overall_feedback',
        'total_score'
    ];

    protected $casts = [
        'ratings' => 'array',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
