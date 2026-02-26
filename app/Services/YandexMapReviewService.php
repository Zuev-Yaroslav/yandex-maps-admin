<?php

namespace App\Services;

use App\Exceptions\HttpWebException;
use App\Exceptions\ReviewException;
use App\Jobs\YandexMap\Review\SyncReviews;
use App\Models\Subsidiary;
use App\Models\YandexMapSetting;
use App\Repositories\YandexMap\ReviewRepository;
use App\Repositories\YandexMap\SubsidiaryRepository;
use App\Services\YandexMap\ApiClient\OrgReviewApiClient;
use App\Services\YandexMap\ReviewHtmlParser;
use App\Services\YandexMap\WebClient\OrgReviewWebClient;
use App\Support\HashHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class YandexMapReviewService
{
    public static ?string $sessionId;

    public static function index(Subsidiary $subsidiary, $page = 1)
    {

        $reviews = $subsidiary->reviews()->latest('updatedTime')->paginate(50);

        return $reviews;
    }

    public static function crawlAndFetchReviews(string $orgId, int $page = 1): array
    {
        $orgReviewWebClient = OrgReviewWebClient::make();

        $html = $orgReviewWebClient->fetchReviewsHtml($orgId);
        self::$sessionId = ReviewHtmlParser::getSessionId($html);
        if (! self::$sessionId) {
            Log::info('Append proxy');
            $html = $orgReviewWebClient
                ->appendProxy(config('proxy.url'))
                ->fetchReviewsHtml($orgId);
            self::$sessionId = ReviewHtmlParser::getSessionId($html);
            HttpWebException::checkSessionIdOnNull(self::$sessionId, $html);
            Log::info('Proxy is working');
        }

        $reviews = self::fetchReviewsWithSessionId($orgId, self::$sessionId);
        ReviewException::checkEmptyReviews($reviews);
        $reviews['ratingData'] = ReviewHtmlParser::getRatingData($html);
        $reviews['subsidiaryName'] = ReviewHtmlParser::getOrganizationName(new Crawler($html));

        return $reviews;
    }

    public static function fetchReviewsWithSessionId(string $orgId, string $sessionId, int $page = 1): ?array
    {
        $orgReviewApiClient = OrgReviewApiClient::make();
        $csrfToken = $orgReviewApiClient->fetchAndGetCsrfToken();

        $params = self::getParams($orgId, $csrfToken, $sessionId, $page);
        self::appendSignatureS($params);

        return $orgReviewApiClient->fetchReviews($params);
    }

    public static function syncReviews(YandexMapSetting $yandexMapSetting): void
    {
        $reviewsRawData = YandexMapReviewService::crawlAndFetchReviews($yandexMapSetting->subsidiary_id);
        $subsidiary = SubsidiaryRepository::updateOrCreate([
            'businessId' => $reviewsRawData['reviews'][0]['businessId'],
            'name' => $reviewsRawData['subsidiaryName'],
            'ratingCount' => $reviewsRawData['ratingData']['ratingCount'],
            'ratingValue' => $reviewsRawData['ratingData']['ratingValue'],
            'reviewCount' => $reviewsRawData['ratingData']['reviewCount'],
        ]);
        foreach ($reviewsRawData['reviews'] as $review) {
            $review['subsidiary_id'] = $subsidiary->id;
            ReviewRepository::updateOrCreate($review);
        }
        SyncReviews::dispatch(
            $yandexMapSetting->subsidiary_id,
            $subsidiary,
            YandexMapReviewService::$sessionId,
            2,
        );
    }

    private static function getParams(
        string $orgId,
        string $csrfToken,
        string $sessionId,
        int $page = 1,
        int $limit = 50,
        string $ranking = 'by_time',
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
