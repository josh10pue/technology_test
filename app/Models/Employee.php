<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'gender_id',
        'name',
        'last_name',
        'birthdate',
    ];

    public function job()
    {
        return $this->hasOne(Job::class, 'id', 'job_id');
    }
}
