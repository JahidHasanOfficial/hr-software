<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'quota',
        'is_accruable',
        'requires_attachment',
        'color',
        'is_paid',
        'status',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
