<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model
{
    protected $guarded = [];

    public static function findByBusinessId($businessId)
    {
        return self::where('businessId', $businessId)->first();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
