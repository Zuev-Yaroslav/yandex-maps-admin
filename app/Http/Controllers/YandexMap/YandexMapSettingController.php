<?php

namespace App\Http\Controllers\YandexMap;

use App\Http\Controllers\Controller;
use App\Http\Requests\YandexMap\YandexMapSetting\UpdateRequest;
use App\Http\Resources\YandexMap\Setting\YandexMapSettingResource;
use App\Models\YandexMapSetting;
use App\Services\YandexMapSettingService;
use Illuminate\Support\Facades\Cache;

class YandexMapSettingController extends Controller
{
    public function index()
    {
        $yandexMapSetting = auth()->user()->yandexMapSetting;
        $yandexMapSetting = $yandexMapSetting
            ? YandexMapSettingResource::make($yandexMapSetting)->resolve()
            : null;

        return inertia('admin/yandexMap/YandexMapSetting', compact('yandexMapSetting'));
    }

    public function update(UpdateRequest $request)
    {
        $data = $request->validated();
        $yandexMapSetting = YandexMapSettingService::update($data);

        return YandexMapSettingResource::make($yandexMapSetting)->resolve();
    }
}
