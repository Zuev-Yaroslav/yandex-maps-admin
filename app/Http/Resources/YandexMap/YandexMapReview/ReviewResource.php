<?php

namespace App\Http\Resources\YandexMap\YandexMapReview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'author' => $this['author'] ?? null,
            //            'businessComment' => $this['businessComment'],
            //            'businessId' => $this['businessId'],
            //            'photos' => $this['photos'],
            'rating' => $this['rating'] ?? null,
            //            'reactions' => $this['reactions'],
            'reviewId' => $this['reviewId'] ?? null,
            'text' => $this['text'] ?? null,
            //            'textLanguage' => $this['textLanguage'],
            //            'textTranslations' => $this['textTranslations'],
            'updatedTime' => (new Carbon($this['updatedTime']))->format('d.m.Y H:i'),
            //            'videos' => $this['videos'],
        ];
    }

    public static function getMeta(Collection $additional): array
    {
        return [
            'meta' => [
                'params' => $additional['params'] ?? [],
                'tags' => $additional['tags'] ?? [],
                'aspects' => $additional['aspects'] ?? [],
                'ratingData' => [
                    'ratingCount' => $additional['ratingData']['ratingCount'] ?? 0,
                    'ratingValue' => self::getFormattedRatingValue($additional['ratingData']['ratingValue']),
                    'reviewCount' => $additional['ratingData']['reviewCount'] ?? 0,
                ],
                'organizationName' => $additional['organizationName'] ?? null,
            ],
        ];
    }

    private static function getFormattedRatingValue($value): string
    {
        $ratingValue = (float) $value ?? 0;

        return number_format($ratingValue, 1, '.', '');
    }
}
