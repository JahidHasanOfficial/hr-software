<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'user_id',
        'gross_monthly',
        'net_monthly',
        'currency',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(EmployeeSalaryDetail::class);
    }
}
