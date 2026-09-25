<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveMember extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'category_id' => 'integer',
        'personnel_id' => 'integer',
    ];

    protected $appends = ['name', 'avatar', 'email', 'phone', 'degrees'];

    public function category()
    {
        return $this->belongsTo(ExecutiveCategory::class, 'category_id');
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }

    public function getNameAttribute(): string
    {
        if (!empty($this->custom_name)) {
            return $this->custom_name;
        }

        if ($this->personnel) {
            $pName = trim($this->personnel->name);
            $acad = trim((string)$this->personnel->academic_title);
            if (!empty($acad)) {
                $prefixes = ['ผศ.', 'รศ.', 'ศ.', 'ดร.', 'อาจารย์', 'นาง', 'นาย', 'นางสาว'];
                foreach ($prefixes as $pf) {
                    if (str_starts_with($pName, $pf)) {
                        return $pName;
                    }
                }
                if (!str_starts_with($pName, $acad)) {
                    return $acad . ' ' . $pName;
                }
            }
            return $pName;
        }

        return '';
    }

    public function getAvatarAttribute(): ?string
    {
        if (!empty($this->custom_avatar)) {
            return $this->custom_avatar;
        }

        return $this->personnel ? $this->personnel->avatar : null;
    }

    public function getEmailAttribute(): ?string
    {
        if (!empty($this->custom_email)) {
            return $this->custom_email;
        }

        return $this->personnel ? $this->personnel->email : null;
    }

    public function getPhoneAttribute(): ?string
    {
        if (!empty($this->custom_phone)) {
            return $this->custom_phone;
        }

        return $this->personnel ? $this->personnel->phone : null;
    }

    public function getDegreesAttribute(): ?string
    {
        return $this->personnel ? $this->personnel->degrees : null;
    }
}
