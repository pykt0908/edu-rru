<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulationCategory extends Model
{
    protected $table = 'regulation_categories';

    protected $fillable = [
        'key',
        'name',
        'short_name',
        'description',
        'icon',
        'color',
        'badge_class',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(RegulationItem::class, 'category', 'key');
    }
}
