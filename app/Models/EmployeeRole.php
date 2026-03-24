<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRole extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_employee_role')->withTimestamps();
    }
}
