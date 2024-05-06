<?php

namespace App\ApiClients\Contracts;

interface ApiClientInterface
{
    public function sendRequest(string $method, string $url, array $data = []): array;
}
