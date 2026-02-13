<?php

namespace App\Services\YandexMap;

use Symfony\Component\DomCrawler\Crawler;

class ReviewHtmlParser
{
    public static function getSessionId(string $html): ?string
    {
        preg_match('/"sessionId":"(.*?)"/', $html, $matches);

        return $matches[1] ?? null;
    }

    public static function getRatingData(string $html): ?array
    {
        if (preg_match('/"ratingData":\s*(\{[^}]+\})/', $html, $matches)) {
            return json_decode($matches[1], true);
        }

        return null;
    }

    public static function getOrganizationName(Crawler $crawler): ?string
    {
        return $crawler->filter('.orgpage-header-view__header')->count() > 0
            ? $crawler->filter('.orgpage-header-view__header')->text()
            : null;
    }
}
