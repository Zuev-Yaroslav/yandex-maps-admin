<?php

namespace App\Http\Controllers\YandexMap;

use App\Http\Controllers\Controller;
use App\Http\Resources\YandexMap\Review\YandexMapReviewResource;
use App\Http\Resources\YandexMap\Setting\YandexMapSettingResource;
use App\Http\Resources\YandexMap\Subsidiary\YandexMapSubsidiaryResource;
use App\Models\Subsidiary;
use App\Services\YandexMapReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class YandexMapReviewController extends Controller
{
    public function index()
    {
        $yandexMapSetting = auth()->user()->yandexMapSetting;
        $subsidiary = Subsidiary::findByBusinessId($yandexMapSetting?->subsidiary_id);
        $reviews = $subsidiary ? YandexMapReviewService::index($subsidiary) : null;
        $subsidiary = $subsidiary ? YandexMapSubsidiaryResource::make($subsidiary)->resolve() : null;

        $reviews = isset($reviews)
            ? YandexMapReviewResource::collection($reviews)
            : [];
        $yandexMapSetting = $yandexMapSetting ? YandexMapSettingResource::make($yandexMapSetting)->resolve() : null;

        return inertia('admin/yandexMap/review/YandexMapReviewIndex', compact('reviews', 'yandexMapSetting', 'subsidiary'));
    }

    public function syncReviews()
    {
        $yandexMapSetting = auth()->user()->yandexMapSetting()->firstOrFail();

        YandexMapReviewService::syncReviews($yandexMapSetting);

        return redirect()->route('admin.yandex-maps.reviews.index');
    }
}
