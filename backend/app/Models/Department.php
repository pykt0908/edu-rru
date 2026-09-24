<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'slug',
        'name',
        'degree_title',
        'head_personnel_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function personnels()
    {
        return $this->hasMany(Personnel::class, 'department_id', 'slug');
    }

    public function head()
    {
        return $this->belongsTo(Personnel::class, 'head_personnel_id', 'id');
    }
}
