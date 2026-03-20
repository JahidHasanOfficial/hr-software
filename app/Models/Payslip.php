<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'user_id',
        'payroll_batch_id',
        'payslip_no',
        'basic_salary',
        'total_earnings',
        'total_deductions',
        'net_payable',
        'earnings_snapshot',
        'deductions_snapshot',
        'payment_method',
        'status'
    ];

    protected $casts = [
        'earnings_snapshot' => 'array',
        'deductions_snapshot' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(PayrollBatch::class, 'payroll_batch_id');
    }
}
