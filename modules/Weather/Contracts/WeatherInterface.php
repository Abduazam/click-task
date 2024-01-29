<?php

namespace Modules\Weather\Contracts;

interface WeatherInterface
{
    public function setApiUrl();

    public function getApiUrl();

    public function setApiKey();

    public function getApiKey();

    public function setResponseLang();

    public function getResponseLang();

    public function requestUrl();

    public function sendRequest();
}
