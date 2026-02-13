<?php

namespace App\Services;

use App\Models\YandexMapSetting;
use Illuminate\Support\Facades\Cache;

class YandexMapSettingService
{
    public static function update(array $data): YandexMapSetting
    {
        $yandexMapSetting = YandexMapSetting::updateOrCreate([
            'user_id' => auth()->id(),
        ], $data);
        Cache::forget('yandex-maps.user.'.auth()->id().'.organization.reviews');

        return $yandexMapSetting;
    }
}
