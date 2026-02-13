<?php

namespace App\Services\YandexMap\WebClient;

use App\Exceptions\HttpWebException;

class OrgReviewWebClient extends BaseWebClient
{
    public function fetchReviewsHtml(string $orgId): string
    {
        return $this->http->get("org/{$orgId}/reviews")->body();
    }

    public function appendProxy(): self
    {
        $this->http->withOptions([
            'proxy' => config('proxy.url'),
            'verify' => false,
        ]);

        return $this;
    }
}
