<?php

namespace App\Http\Resources\YandexMap\Subsidiary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YandexMapSubsidiaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ratingCount' => $this['ratingCount'] ?? 0,
            'ratingValue' => self::getFormattedRatingValue($this['ratingValue']),
            'reviewCount' => $this['reviewCount'] ?? 0,
            'name' => $this['name'] ?? null,
        ];
    }

    private static function getFormattedRatingValue($value): string
    {
        $ratingValue = (float) $value ?? 0;

        return number_format($ratingValue, 1, '.', '');
    }
}
