<?php

use App\Http\Controllers\YandexMap\YandexMapReviewController;
use App\Http\Controllers\YandexMap\YandexMapSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/yandex-maps/setting', [YandexMapSettingController::class, 'index'])
            ->name('admin.yandex-maps.setting');
        Route::patch('/yandex-maps/setting', [YandexMapSettingController::class, 'update'])
            ->name('admin.yandex-maps.setting.update');
        Route::get('/yandex-maps/reviews', [YandexMapReviewController::class, 'index'])
            ->name('admin.yandex-maps.reviews.index');
        Route::post('/yandex-maps/reviews/sync', [YandexMapReviewController::class, 'syncReviews'])
            ->name('admin.yandex-maps.reviews.sync');
    });
