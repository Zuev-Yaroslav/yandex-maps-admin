<?php

namespace App\Http\Resources\YandexMap\YandexMapSetting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YandexMapSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'org_reviews_url' => $this->org_reviews_url,
        ];
    }


}
