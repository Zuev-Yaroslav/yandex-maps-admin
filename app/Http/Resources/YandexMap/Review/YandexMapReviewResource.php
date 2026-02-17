<?php

namespace App\Http\Resources\YandexMap\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class YandexMapReviewResource extends JsonResource
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
            'businessComment' => $this['businessComment'] ?? null,
            'businessId' => $this['businessId'] ?? null,
            'photos' => $this['photos'] ?? null,
            'rating' => $this['rating'] ?? null,
            'reactions' => $this['reactions'] ?? null,
            'reviewId' => $this['reviewId'] ?? null,
            'text' => $this['text'] ?? null,
            'textLanguage' => $this['textLanguage'],
            'textTranslations' => $this['textTranslations'],
            'updatedTime' => (new Carbon($this['updatedTime']))->format('d.m.Y H:i'),
            'videos' => $this['videos'],
        ];
    }
}
