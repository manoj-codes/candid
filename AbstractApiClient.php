<?php

namespace App\ApiClients;

use App\ApiClients\Contracts\ApiClientInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

abstract class AbstractApiClient implements ApiClientInterface
{
    protected $baseUrl;
    protected $accessToken;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->accessToken = $this->getAccessToken();
    }

    protected function getAccessToken(): string
    {
        return Cache::remember('api_access_token', 55, function () {
            return $this->fetchAccessToken();
        });
    }

    private function fetchAccessToken(): string
    {
        $response = Http::post($this->baseUrl . '/oauth/token', [
            'grant_type'    => 'client_credentials',
            'client_id'     => config('api.client_id'),
            'client_secret' => config('api.client_secret'),
        ]);

        return $response->json('access_token');
    }

    abstract public function sendRequest(string $method, string $url, array $data = []): array;
}
