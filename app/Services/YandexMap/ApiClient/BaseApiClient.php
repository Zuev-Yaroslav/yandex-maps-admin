<?php

namespace App\Services\YandexMap\ApiClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class BaseApiClient
{
    protected PendingRequest $http;

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
        $this->http = Http::apiYaMap();
    }
}
