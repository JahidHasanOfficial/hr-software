<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequisition extends Model
{
    protected $fillable = [
        'title',
        'department_id',
        'designation_id',
        'headcount',
        'urgency_level',
        'justification',
        'budget_details',
        'status',
        'requested_by'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }
}
