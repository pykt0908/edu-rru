<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyHistory extends Model
{
    protected $table = 'faculty_history';

    protected $fillable = ['year', 'title', 'detail', 'sort_order'];
}
