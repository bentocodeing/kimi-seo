<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'image_url',
        'link_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Active ads for the public slot, in display order: lowest sort_order
     * first, then newest. The slot rotates through all of them.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, self>
     */
    public static function activeOrdered()
    {
        return static::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();
    }

    public function image(): ?string
    {
        if ($this->image_path) {
            // Relative URL: host-independent, works regardless of APP_URL.
            return '/storage/'.$this->image_path;
        }

        return $this->image_url;
    }
}
