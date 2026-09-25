<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function members()
    {
        return $this->hasMany(ExecutiveMember::class, 'category_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }
}
