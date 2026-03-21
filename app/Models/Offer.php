<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'candidate_id',
        'offered_salary',
        'joining_date',
        'terms_and_conditions',
        'status'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'offered_salary' => 'decimal:2',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
