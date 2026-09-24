<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItaYear extends Model
{
    protected $table = 'ita_years';

    protected $fillable = [
        'year',
        'title',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(ItaItem::class, 'year', 'year');
    }
}
