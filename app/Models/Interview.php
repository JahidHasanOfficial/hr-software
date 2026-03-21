<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'candidate_id',
        'title',
        'scheduled_at',
        'location',
        'interview_type',
        'status',
        'feedback'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviewers()
    {
        return $this->belongsToMany(User::class, 'interview_interviewer');
    }

    public function scorecards()
    {
        return $this->hasMany(Scorecard::class);
    }
}
