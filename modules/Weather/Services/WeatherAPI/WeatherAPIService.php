<?php

namespace Modules\Weather\Services\WeatherAPI;

use Throwable;
use GuzzleHttp\Client;
use Modules\Weather\Contracts\Weather;
use Modules\Weather\Contracts\WeatherInterface;

class WeatherAPIService extends Weather implements WeatherInterface
{
    private string $apiUrl;
    private string $apiKey;
    private string $apiLang;
    private Client $httpClient;
    private string $city;

    public function __construct(Client $httpClient, string $city)
    {
        $this->apiUrl = $this->setApiUrl();
        $this->apiKey = $this->setApiKey();
        $this->apiLang = $this->setResponseLang();
        $this->httpClient = $httpClient;
        $this->city = $city;
    }

    public function setApiUrl()
    {
        return config('weather.weather-api.api-url');
    }

    public function setApiKey()
    {
        return config('weather.weather-api.api-key');
    }

    public function setResponseLang()
    {
        return config('weather.weather-api.lang');
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getResponseLang(): string
    {
        return $this->apiLang;
    }

    public function requestUrl(): string
    {
        return $this->getApiUrl() . "?key=" . $this->getApiKey() . "&q=" . $this->city;
    }

    public function sendRequest()
    {
        try {
            $response = $this->httpClient->get($this->requestUrl());

            return json_decode($response->getBody(), true);
        } catch (Throwable $exception) {
            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
