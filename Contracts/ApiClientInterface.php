<?php

namespace App\ApiClients\Contracts;

interface ApiClientInterface
{
    /**
     * Send a request to the specified endpoint using the provided HTTP method and data.
     *
     * @param string $method The HTTP method to use for the request.
     * @param string $url The endpoint URL to send the request to.
     * @param array $data The data to send with the request, if any.
     * @return array The API response as an associative array.
     */
    public function sendRequest($method, $url, $data = []);
}
