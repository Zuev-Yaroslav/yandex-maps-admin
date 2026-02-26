<?php

namespace App\Services\YandexMap\WebClient;



class OrgReviewWebClient extends BaseWebClient
{

    public function fetchReviewsHtml(string $orgId): string
    {
        return $this->http->get("org/{$orgId}/reviews")->body();
    }

    public function appendProxy(string $proxyUrl): self
    {
        $this->http->withOptions([
            'proxy' => $proxyUrl,
            'verify' => false,
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, // Принудительно IPv4
            ],
        ]);

        return $this;
    }
}
