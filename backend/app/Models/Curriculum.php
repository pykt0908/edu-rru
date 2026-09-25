<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curricula';

    protected $guarded = ['id'];

    protected $casts = [
        'tags'           => 'array',
        'detail_content' => 'array',
        'highlight'      => 'boolean',
        'is_active'      => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'slug');
    }
}
