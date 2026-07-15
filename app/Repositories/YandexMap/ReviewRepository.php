<?php

namespace App\Repositories\YandexMap;

use App\Jobs\YandexMap\Review\SyncReviews;
use App\Models\Review;
use App\Models\Subsidiary;
use App\Models\YandexMapSetting;
use App\Services\YandexMapReviewService;

class ReviewRepository
{
    public static function updateOrCreate(array $data)
    {
        return Review::updateOrCreate(
            ['reviewId' => $data['reviewId']],
            [
                'author' => $data['author'] ?? '',
                'businessComment' => $data['businessComment'] ?? '',
                'businessId' => $data['businessId'] ?? '',
                'photos' => $data['photos'] ?? '',
                'rating' => $data['rating'] ?? '',
                'reactions' => $data['reactions'] ?? '',
                'reviewId' => $data['reviewId'] ?? '',
                'text' => $data['text'] ?? '',
                'textLanguage' => $data['textLanguage'] ?? '',
                'textTranslations' => $data['textTranslations'] ?? '',
                'updatedTime' => $data['updatedTime'] ?? '',
                'videos' => $data['videos'] ?? '',
            ],
        );
    }
}
