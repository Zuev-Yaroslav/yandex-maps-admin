<?php

namespace App\Services\YandexMap\ApiClient;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrgReviewApiClient extends BaseApiClient
{
    public function fetchReviews(array $params): ?array
    {
        $response = $this->http->get('fetchReviews', $params);

        return $response->json('data');
    }

    public function fetchAndGetCsrfToken(): string
    {
        return $this->http->get('fetchReviews')->collect()['csrfToken'];
    }
}
