<?php

namespace App\Services\YandexMap\WebClient;

use App\Exceptions\RenderException;
use App\Services\ProxyService;
use Exception;
use Illuminate\Support\Facades\Log;

class OrgReviewWebClient extends BaseWebClient
{

    public function fetchReviewsHtml(string $orgId): string
    {
        try {
            return $this->http->get("org/{$orgId}/reviews")->body();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            $this->appendProxy(ProxyService::getWorkingProxyUrl());

            return $this->fetchReviewsHtml($orgId);
        }

    }

    public function appendProxy(?string $proxyUrl = null): self
    {
        $this->http->withOptions([
            'proxy' => $proxyUrl ?? config('proxy.url'),
        ]);

        return $this;
    }
}
