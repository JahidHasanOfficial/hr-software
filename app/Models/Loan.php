<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'loan_type',
        'amount',
        'emi_amount',
        'paid_amount',
        'remaining_amount',
        'start_date',
        'end_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
