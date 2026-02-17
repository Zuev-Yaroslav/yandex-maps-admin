<?php

namespace App\Repositories\YandexMap;

use App\Jobs\YandexMap\Review\SyncReviews;
use App\Models\Review;
use App\Models\Subsidiary;
use App\Models\YandexMapSetting;
use App\Services\YandexMapReviewService;

class ReviewRepository
{
    public static function updateOrCreate(array $data): Review
    {
        return Review::updateOrCreate(
            ['reviewId' => $data['reviewId']],
            $data,
        );
    }
}
