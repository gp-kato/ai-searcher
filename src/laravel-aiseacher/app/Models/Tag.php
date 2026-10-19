<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function tools(): belongsToMany
    {
        return $this->belongsToMany(Tool::class, 'tool_tag', 'tag_id', 'tool_id');
    }
}
