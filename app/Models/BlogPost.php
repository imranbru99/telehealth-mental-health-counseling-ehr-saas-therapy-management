<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'cover_image',
        'locale',
        'category',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                // If package ImranDev\UniversalSlug is available, use it, or fallback to Str::slug
                if (class_exists(\ImranDev\UniversalSlug\UniversalSlug::class)) {
                    $post->slug = \ImranDev\UniversalSlug\UniversalSlug::generate($post->title);
                } else {
                    $post->slug = Str::slug($post->title);
                }
            }
        });
    }
}
