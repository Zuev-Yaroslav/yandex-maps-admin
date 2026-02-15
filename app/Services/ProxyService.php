<?php

namespace App\Services;

use App\Services\YandexMap\WebClient\OrgReviewWebClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProxyService
{
    private static ?array $proxies = null;

    public static function getProxies(): array
    {
        return array_unique(Storage::json('proxies.json'));
    }

    public static function getWorkingProxyUrl(): string
    {
        if (is_null(self::$proxies)) {
            self::$proxies = self::getProxies();
        }

        if (count(self::$proxies) === 0) {
            return '';
        }
        $maxIndex = count(self::$proxies) - 1;
        $index = rand(0, $maxIndex);
        $url = 'http://'.self::$proxies[$index];
        unset(self::$proxies[$index]);
        self::$proxies = array_values(self::$proxies);
        Log::info($url);
        Log::info(count(self::$proxies));

        return $url;
    }
}
