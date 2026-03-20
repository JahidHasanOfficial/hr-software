<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollBatch extends Model
{
    protected $fillable = [
        'month',
        'year',
        'total_gross',
        'total_deductions',
        'total_net',
        'processed_by',
        'status', // draft, locked, paid
        'approved_at',
        'paid_at'
    ];

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }
}
