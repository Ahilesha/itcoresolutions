<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'punch_in',
        'punch_out',
    ];

    protected $casts = [
        'date' => 'date',
        'punch_in' => 'datetime',
        'punch_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
