<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWorkedHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'worked_hours',
        'worked_date',
    ];
}
