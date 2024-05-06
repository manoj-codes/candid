<?php

namespace App\ApiClients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FXRatesApiClient extends AbstractApiClient
{
    public function sendRequest(string $method, string $url, array $data = []): array
    {
        try {
            $response = Http::withToken($this->accessToken)
                            ->$method($this->baseUrl . $url, $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to fetch FX rates', [
                    'url' => $url,
                    'status' => $response->status(),
                    'error' => $response->body(),
                ]);
                throw new \Exception('Failed to fetch FX rates');
            }
        } catch (\Throwable $e) {
            Log::error('API request exception', ['exception' => $e->getMessage()]);
            throw new \Exception('API request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getFXRates(string $currencyPair): array
    {
        return $this->sendRequest('get', "/fxrates/$currencyPair");
    }
}
