<?php

namespace Modules\Weather\Services\WeatherAPI;

use GuzzleHttp\Client;

class WeatherAPI
{
    private array $result;
    private WeatherAPIService $weatherAPIService;

    public function __construct(Client $httpClient, string $city) {
        $this->weatherAPIService = new WeatherAPIService($httpClient, $city);
    }

    public function endpoint(): void
    {
        $this->result = $this->weatherAPIService->sendRequest();
    }

    public function getWeather(): array
    {
        return $this->result['current']['condition'] ?? [];
    }
}
