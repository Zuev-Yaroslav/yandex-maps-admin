<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class Review extends Model
{
    protected $guarded = [];

    protected $casts = [
        'author' => 'array',
        'businessComment' => 'array',
        'photos' => 'array',
        'reactions' => 'array',
        'textTranslations' => 'array',
        'videos' => 'array',
    ];

    public function subsidiary(): BelongsTo
    {
        return $this->belongsTo(Subsidiary::class);
    }
}
