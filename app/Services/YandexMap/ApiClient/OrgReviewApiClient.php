<?php

namespace App\Services\YandexMap\ApiClient;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrgReviewApiClient extends BaseApiClient
{
    public function fetchReviews(array $params): Collection
    {
        $response = $this->http->get('fetchReviews', $params);
        Log::info('response', $response->json());

        return $response->collect('data');
    }

    public function fetchAndGetCsrfToken(): string
    {
        return $this->http->get('fetchReviews')->collect()['csrfToken'];
    }
}
