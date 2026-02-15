<?php

namespace App\Services;

use App\Exceptions\HttpWebException;
use App\Exceptions\ReviewException;
use App\Models\YandexMapSetting;
use App\Services\YandexMap\ApiClient\OrgReviewApiClient;
use App\Services\YandexMap\ReviewHtmlParser;
use App\Services\YandexMap\WebClient\OrgReviewWebClient;
use App\Support\HashHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class OrgReviewService
{
    public static function index(YandexMapSetting $yandexMapSetting): Collection
    {
        $reviews = Cache::remember('yandex-maps.user.'.auth()->id().'.organization.reviews', 60 * 60, function () use ($yandexMapSetting) {
            return OrgReviewService::getReviews($yandexMapSetting->organization_id);
        });

        return $reviews;
    }

    public static function getReviews(string $orgId): Collection
    {
        $orgReviewWebClient = OrgReviewWebClient::make();
        $orgReviewApiClient = OrgReviewApiClient::make();

        $html = $orgReviewWebClient->fetchReviewsHtml($orgId);
        $sessionId = ReviewHtmlParser::getSessionId($html);
        if (! $sessionId) {
            Log::info('Append proxy');
            $html = $orgReviewWebClient
                ->appendProxy(ProxyService::getWorkingProxyUrl())
                ->fetchReviewsHtml($orgId);
            $sessionId = ReviewHtmlParser::getSessionId($html);
            HttpWebException::checkSessionIdOnNull($sessionId, $html);
            Log::info('Proxy is working');
        }

        $csrfToken = $orgReviewApiClient->fetchAndGetCsrfToken();
        $params = self::getParams($orgId, $csrfToken, $sessionId);
        self::appendSignatureS($params);

        $reviews = $orgReviewApiClient->fetchReviews($params);
        Log::info('reviews', $reviews->toArray());
        ReviewException::checkEmptyReviews($reviews->toArray());
        $reviews['ratingData'] = ReviewHtmlParser::getRatingData($html);
        $reviews['organizationName'] = ReviewHtmlParser::getOrganizationName(new Crawler($html));

        return $reviews;
    }

    private static function getParams(
        string $orgId,
        string $csrfToken,
        string $sessionId,
        int $page = 1,
        int $limit = 50,
        string $ranking = 'by_relevance_org',
        string $locale = 'ru_RU',
    ): array {
        return [
            'ajax' => 1,
            'businessId' => $orgId,
            'csrfToken' => $csrfToken,
            'locale' => $locale,
            'page' => $page,
            'pageSize' => $limit,
            'ranking' => $ranking,
            'reqId' => time().'000000-'.rand(1000, 9999).'-sas1-'.rand(1000, 9999), // Эмуляция reqId
            'sessionId' => $sessionId,
        ];
    }

    private static function appendSignatureS(array &$params): void
    {
        $queryString = http_build_query($params);
        $params['s'] = HashHelper::djb2Hash($queryString);
    }
}
