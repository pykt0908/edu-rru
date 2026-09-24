<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhilosophySetting extends Model
{
    protected $table = 'philosophy_settings';

    protected $fillable = ['key', 'value'];
}
