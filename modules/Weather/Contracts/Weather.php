<?php

namespace Modules\Weather\Contracts;

use GuzzleHttp\Client;

abstract class Weather
{
    private string $apiUrl;
    private string $apiKey;
    private string $apiLang;
    private Client $httpClient;
    private string $city;
}
