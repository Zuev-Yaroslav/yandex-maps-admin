<?php

namespace App\Services;

use App\Jobs\YandexMap\Review\SyncReviews;
use App\Models\YandexMapSetting;
use App\Repositories\YandexMap\ReviewRepository;
use App\Repositories\YandexMap\SubsidiaryRepository;
use Illuminate\Support\Facades\Cache;

class YandexMapSettingService
{
    public static function update(array $data): YandexMapSetting
    {
        $yandexMapSetting = YandexMapSetting::updateOrCreate([
            'user_id' => auth()->id(),
        ], $data);

        return $yandexMapSetting;
    }
}
