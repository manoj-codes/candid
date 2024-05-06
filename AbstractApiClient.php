<?php

namespace App\ApiClients;

use GuzzleHttp\Client;
use App\ApiClients\Contracts\ApiClientInterface;
use Illuminate\Support\Facades\Cache;

abstract class AbstractApiClient implements ApiClientInterface
{
    protected $baseUrl;
    protected $accessToken;

    /**
     * Create a new API client instance.
     *
     * @param string $baseUrl The base URL for the API.
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Retrieve or generate a new access token.
     *
     * @return string The access token.
     */
    protected function getAccessToken()
    {
        return Cache::remember('api_access_token', 55, function () {
            return $this->fetchAccessToken();
        });
    }

    /**
     * Fetch a new access token from the authentication server.
     *
     * @return string The newly fetched access token.
     */
    private function fetchAccessToken()
    {
        $client = new Client();
        $response = $client->post($this->baseUrl . '/oauth/token', [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('api.client_id'),
                'client_secret' => config('api.client_secret'),
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];
    }

    /**
     * Send a request to the API.
     * Subclasses must implement this method to handle specific endpoint interactions.
     *
     * @param string $method HTTP method.
     * @param string $url URL path.
     * @param array $data Data to be sent with the request.
     * @return array Response from the API.
     */
    abstract public function sendRequest($method, $url, $data = []);
}
