<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItaItem extends Model
{
    protected $table = 'ita_items';

    protected $fillable = [
        'year',
        'code',
        'indicator',
        'components',
        'links',
        'sort_order',
    ];

    protected $casts = [
        'links' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Accessor for components:
     * If stored as a JSON array (from legacy seeder), convert to clean HTML string.
     * If stored as HTML string, return directly.
     */
    public function getComponentsAttribute($value)
    {
        if (empty($value)) {
            return '';
        }

        // If it's already an array (due to any previous casting or parsing)
        if (is_array($value)) {
            return $this->convertLegacyArrayToHtml($value);
        }

        // If it's a JSON string representing an array: [{"text": ...}]
        if (is_string($value) && (str_starts_with(trim($value), '[') || str_starts_with(trim($value), '{'))) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $this->convertLegacyArrayToHtml($decoded);
            }
        }

        return $value;
    }

    /**
     * Mutator for components:
     * Accepts either string (HTML from RichTextEditor) or array.
     */
    public function setComponentsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['components'] = json_encode($value, JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['components'] = $value ?? '';
        }
    }

    private function convertLegacyArrayToHtml(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            if (is_string($item)) {
                $html .= '<p>' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</p>';
            } elseif (is_array($item)) {
                $text = $item['text'] ?? '';
                $html .= '<p><strong>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</strong></p>';
                if (!empty($item['subnotes']) && is_array($item['subnotes'])) {
                    $html .= '<ul>';
                    foreach ($item['subnotes'] as $sub) {
                        $html .= '<li>' . htmlspecialchars($sub, ENT_QUOTES, 'UTF-8') . '</li>';
                    }
                    $html .= '</ul>';
                }
            }
        }
        return $html;
    }
}
