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
                'author' => $data['author'] ?? null,
                'businessComment' => $data['businessComment'] ?? null,
                'businessId' => $data['businessId'] ?? null,
                'photos' => $data['photos'] ?? null,
                'rating' => $data['rating'] ?? null,
                'reactions' => $data['reactions'] ?? null,
                'text' => $data['text'] ?? null,
                'textLanguage' => $data['textLanguage'] ?? null,
                'textTranslations' => $data['textTranslations'] ?? null,
                'updatedTime' => $data['updatedTime'] ?? null,
                'videos' => $data['videos'] ?? null,
                'subsidiary_id' => $data['subsidiary_id'],
            ],
        );
    }
}
