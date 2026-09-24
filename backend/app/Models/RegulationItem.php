<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulationItem extends Model
{
    protected $table = 'regulation_items';

    protected $fillable = [
        'title',
        'category',
        'year',
        'effective_date',
        'file_size',
        'file_url',
        'description',
        'sort_order',
    ];

    public function categoryRelation()
    {
        return $this->belongsTo(RegulationCategory::class, 'category', 'key');
    }
}
