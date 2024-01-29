<?php

namespace Modules\Weather\Services\OpenWeatherMap;

use GuzzleHttp\Client;

class OpenWeatherMap
{
    private array $result;
    private OpenWeatherMapService $openWeatherMapService;

    public function __construct(Client $httpClient, string $city) {
        $this->openWeatherMapService = new OpenWeatherMapService($httpClient, $city);
    }

    public function endpoint(): void
    {
        $this->result = $this->openWeatherMapService->sendRequest();
    }

    public function getWeather(): array
    {
        $this->result['weather'][0]['text'] = $this->result['weather'][0]["description"];
        unset($this->result['weather'][0]["description"]);

        return $this->result['weather'][0] ?? [];
    }
}
