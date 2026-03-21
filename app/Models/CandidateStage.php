<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateStage extends Model
{
    protected $fillable = [
        'name',
        'order'
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
