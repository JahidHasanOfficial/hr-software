<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = ['branch_id', 'shift_id', 'name', 'description', 'status'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
