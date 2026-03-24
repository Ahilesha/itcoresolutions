<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'name',
        'phone',
        'email',
        'designation',
        'joining_date',
        'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(EmployeeRole::class, 'employee_employee_role')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
