<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'url',
        'description',
        'pricing_system',
        'thumbnail',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function functions(): BelongsToMany
    {
        return $this->belongsToMany(ToolFunction::class, 'tool_function', 'tool_id', 'function_id')->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_tool', 'tool_id', 'tag_id')->withTimestamps();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(ToolFavorite::class);
    }
}
