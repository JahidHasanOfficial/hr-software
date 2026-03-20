<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type', // earning, deduction, reimbursement
        'unit', // fixed, percentage
        'is_taxable',
        'is_statutory',
        'status'
    ];
}
