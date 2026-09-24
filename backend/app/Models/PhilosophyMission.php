<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhilosophyMission extends Model
{
    protected $table = 'philosophy_missions';

    protected $fillable = ['text', 'sort_order'];
}
