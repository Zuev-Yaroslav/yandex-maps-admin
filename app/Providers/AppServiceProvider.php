<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $cookieJar = new CookieJar;
        Http::macro('apiYaMap', function () use ($cookieJar) {
            return Http::withOptions(['cookies' => $cookieJar])
                ->acceptJson()
                ->timeout(100)
                ->connectTimeout(30)
                ->baseUrl('https://yandex.ru/maps/api/business/');
        });
        Http::macro('webYaMap', function () use ($cookieJar) {
            $userAgent = request()->userAgent();
            $acceptLanguage = request()->header('Accept-Language');

            return Http::withOptions([
                'cookies' => $cookieJar,
            ])
                ->withHeaders(array_filter([
                    'User-Agent' => $userAgent ?? 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0',
                    'Accept' => 'text/html,application/xhtml+xml',
                    'Accept-Language' => $acceptLanguage ?? 'ru-RU,ru;q=0.9',
                    'Sec-CH-UA' => request()->header('Sec-CH-UA'),
                    'Sec-CH-UA-Platform' => request()->header('Sec-CH-UA-Platform'),
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'same-origin',
                    'Upgrade-Insecure-Requests' => '1',
                ]))
                ->timeout(60)->connectTimeout(2)->baseUrl('https://yandex.ru/maps/');
        });

        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
