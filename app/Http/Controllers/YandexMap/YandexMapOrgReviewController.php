<?php

namespace App\Http\Controllers\YandexMap;

use App\Http\Controllers\Controller;
use App\Http\Resources\YandexMap\YandexMapReview\ReviewResource;
use App\Http\Resources\YandexMap\YandexMapSetting\YandexMapSettingResource;
use App\Services\OrgReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class YandexMapOrgReviewController extends Controller
{
    public function index()
    {
        $yandexMapSetting = auth()->user()->yandexMapSetting;
        $reviewsRawData = $yandexMapSetting ? OrgReviewService::index($yandexMapSetting) : null;
        $reviews = isset($reviewsRawData['reviews'])
            ? ReviewResource::collection($reviewsRawData['reviews'])
                ->additional(ReviewResource::getMeta($reviewsRawData->forget('reviews')))
            : [];
        $yandexMapSetting = $yandexMapSetting ? YandexMapSettingResource::make($yandexMapSetting)->resolve() : null;

        return inertia('admin/yandexMap/review/YandexMapReviewIndex', compact('reviews', 'yandexMapSetting'));
    }
}
