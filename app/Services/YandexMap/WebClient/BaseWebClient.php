<?php

namespace App\Services\YandexMap\WebClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;

abstract class BaseWebClient
{
    protected Browsershot|PendingRequest $http;

    private static array $instances = [];

    private static function getInstance(): self
    {
        if (! isset(self::$instances[static::class])) {
            self::$instances[static::class] = new static;
        }

        return self::$instances[static::class];
    }

    public static function make(): static
    {
        return static::getInstance();
    }

    private function __construct()
    {
//        $this->http = (new Browsershot)
//            ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36')
//            ->waitUntilNetworkIdle();

        $this->http = Http::webYaMap();
    }
}
