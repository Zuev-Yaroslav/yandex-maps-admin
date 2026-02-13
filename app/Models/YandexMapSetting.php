<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YandexMapSetting extends Model
{
    protected $fillable = [
        'org_reviews_url',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getOrganizationIdAttribute(): string
    {
        preg_match("/\/org\/.*\/(\d+)/", $this->org_reviews_url, $matches);
        return $matches[1];
    }
}
