<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $fillable = [
        'job_requisition_id',
        'job_code',
        'title',
        'description',
        'employment_type',
        'location',
        'salary_min',
        'salary_max',
        'expiry_date',
        'is_published'
    ];

    public function jobRequisition()
    {
        return $this->belongsTo(JobRequisition::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
