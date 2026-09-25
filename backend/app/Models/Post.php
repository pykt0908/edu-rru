<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'key_highlights' => 'array',
        'quote' => 'array',
        'gallery' => 'array',
        'author' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'sdgs' => 'array',
        'featured' => 'boolean',
    ];

    public function getContentAttribute($value)
    {
        if (is_null($value)) return [];
        $decoded = json_decode($value, true);
        return $decoded !== null ? $decoded : $value;
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
