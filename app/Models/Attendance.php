<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'date', 
        'check_in_time', 
        'check_in_latitude', 
        'check_in_longitude', 
        'check_in_ip',
        'check_out_time', 
        'check_out_latitude', 
        'check_out_longitude', 
        'check_out_ip',
        'stay_minutes', 
        'late_minutes', 
        'early_leaving_minutes', 
        'overtime_minutes', 
        'status', 
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
