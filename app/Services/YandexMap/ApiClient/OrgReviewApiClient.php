<?php

namespace App\Services\YandexMap\ApiClient;



use Illuminate\Support\Collection;

class OrgReviewApiClient extends BaseApiClient
{
    public function fetchReviews(array $params): Collection
    {
        return $this->http->get('fetchReviews', $params)->collect('data');
    }

    public function fetchAndGetCsrfToken(): string
    {
        return $this->http->get('fetchReviews')->collect()['csrfToken'];
    }
}
