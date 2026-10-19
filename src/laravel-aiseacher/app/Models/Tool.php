<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tool extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'url',
        'description',
        'pricing_system',
        'thumbnail',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function functions(): BelongsToMany
    {
        return $this->belongsToMany(ToolFunction::class, 'tool_function', 'tool_id', 'function_id');
    }

    public function tag(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tool_tag', 'tool_id', 'tag_id');
    }
}
