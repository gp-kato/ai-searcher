<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ToolFunction extends Model
{
    protected $table = 'functions';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'tool_function', 'function_id', 'tool_id')->withTimestamps();
    }
}
