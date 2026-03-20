<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    
    protected $fillable = ['company_id', 'shift_id', 'name', 'address', 'email', 'phone', 'status'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
