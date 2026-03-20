<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryDetail extends Model
{
    protected $fillable = [
        'employee_salary_id',
        'salary_component_id',
        'value'
    ];

    public function salary()
    {
        return $this->belongsTo(EmployeeSalary::class, 'employee_salary_id');
    }

    public function component()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }
}
