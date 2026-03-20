<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_time', 'end_time', 'late_threshold', 'early_checkout_threshold', 'break_time', 'half_day_threshold', 'is_flexible', 'status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
