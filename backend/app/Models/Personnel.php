<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'education_history' => 'array',
        'expertise' => 'array',
        'publications' => 'array',
        'courses' => 'array',
        'work_experience' => 'array',
    ];
}
