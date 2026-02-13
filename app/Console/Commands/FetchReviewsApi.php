<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class FetchReviewsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-api:reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $orgId = '1010501395';
        $mainUrl = "https://yandex.ru/maps/org/$orgId/reviews";

        // 1. Создаем Jar для кук, чтобы сессия сохранялась между запросами
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar;

        $this->info("Инициализация сессии для организации: {$orgId}...");
        $html = Browsershot::url($mainUrl)
            ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36')
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox', '--disable-blink-features=AutomationControlled'])
            ->bodyHtml();//
        // 2. Первый запрос: заходим на страницу, чтобы получить куки и токены
//        $initialResponse = Http::withOptions(['cookies' => $cookieJar])
//            ->withHeaders([
//                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
//                'Accept' => 'text/html,application/xhtml+xml',
//                'Accept-Language' => 'ru-RU,ru;q=0.9',
//            ])->get($mainUrl);
//        $html = $initialResponse->body();

        // Извлекаем токены через регулярки
        preg_match('/"csrfToken":"(.*?)"/', $html, $csrfMatch);//
        preg_match('/"sessionId":"(.*?)"/', $html, $sessionMatch);//

        $csrfToken = $csrfMatch[1] ?? null;
        $sessionId = $sessionMatch[1] ?? null; //

//        if (! $csrfToken) {
//            Storage::put('yandex_error.html', $html);
//            $this->error('Ошибка: Не удалось получить csrfToken. Возможно, IP заблокирован или требуется капча.');
//
//            return;
//        }

        // 3. Подготовка параметров для API (порядок важен для хеша s!)
        $page = 1;
        $reqId = time().'000000-'.rand(1000, 9999).'-sas1-'.rand(1000, 9999); // Эмуляция reqId





        // 5. Финальный запрос к API
        $csrfToken = Http::withOptions(['cookies' => $cookieJar])
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
                'Referer' => $mainUrl,
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->get('https://yandex.ru/maps/api/business/fetchReviews')->json()['csrfToken'];//
        $params = [
            'ajax' => 1,
            'businessId' => $orgId,
            'csrfToken' => $csrfToken,
            'locale' => 'ru_RU',
            'page' => $page,
            'pageSize' => 50,
            'ranking' => 'by_time',
            'reqId' => $reqId,
            'sessionId' => $sessionId,
        ];
        // 4. Генерация подписи s (алгоритм DJB2)
        $queryString = http_build_query($params);
        $s = $this->generateYandexHash($queryString);
        $params['s'] = $s;
        $this->info("Запрос к API с подписью s: {$s}");

        $apiResponse = Http::withOptions(['cookies' => $cookieJar])
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
                'Referer' => $mainUrl,
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->get('https://yandex.ru/maps/api/business/fetchReviews', $params);//

        if ($apiResponse->successful()) {
            $data = $apiResponse->json();

            $reviews = $data['data']['reviews'] ?? [];

            if (empty($reviews)) {
                $this->warn('API ответил успешно, но список отзывов пуст.');

                return;
            }

            $this->info('Найдено отзывов: '.count($reviews));

            $tableData = collect($reviews)->map(fn ($r) => [
                $r['author']['name'] ?? 'Аноним',
                $r['rating'] ?? '-',
                Str::limit($r['text'] ?? '', 80),
                $r['updatedTime'] ?? '',
            ]);

            $this->table(['Автор', '⭐', 'Текст', 'Дата'], $tableData);
        } else {
            $this->error('Ошибка API: '.$apiResponse->status());
            $this->line($apiResponse->body());
        }

    }

    /**
     * Реализация алгоритма DJB2 для подписи s
     */
    private function generateYandexHash(string $e): int
    {
        $n = 5381;
        for ($r = 0; $r < strlen($e); $r++) {
            $n = (($n << 5) + $n) ^ ord($e[$r]);
            $n = $n & 0xFFFFFFFF; // Эмуляция 32-битного unsigned int
        }

        return $n;
    }
}
