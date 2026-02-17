<?php

namespace App\Repositories\YandexMap;

use App\Models\Review;
use App\Models\Subsidiary;

class SubsidiaryRepository
{
    public static function updateOrCreate(array $data): Subsidiary
    {
        return Subsidiary::updateOrCreate([
            'businessId' => $data['businessId'],
        ], $data);
    }
}
