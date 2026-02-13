<?php

use App\Http\Controllers\YandexMap\YandexMapOrgReviewController;
use App\Http\Controllers\YandexMap\YandexMapSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/yandex-maps/setting', [YandexMapSettingController::class, 'index'])
            ->name('admin.yandex-maps.setting');
        Route::patch('/yandex-maps/setting', [YandexMapSettingController::class, 'update'])
            ->name('admin.yandex-maps.setting.update');
        Route::get('/yandex-maps/reviews', [YandexMapOrgReviewController::class, 'index'])
            ->name('admin.yandex-maps.reviews.index');
    });
