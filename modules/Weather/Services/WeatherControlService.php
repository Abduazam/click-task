<?php

namespace Modules\Weather\Services;

use GuzzleHttp\Client;
use Modules\Telegram\Telegram;
use Illuminate\Support\Facades\Mail;
use Modules\Weather\Mail\SendWeatherMail;
use Modules\Weather\Services\OpenWeatherMap\OpenWeatherMap;
use Modules\Weather\Services\WeatherAPI\WeatherAPI;

class WeatherControlService
{
    protected array $weather;
    protected string $provider;
    protected string $city;
    protected string $channel;
    protected Client $httpClient;

    public function __construct(string $provider, string $city, string $channel, Client $httpClient)
    {
        $this->provider = $provider;
        $this->city = $city;
        $this->channel = $channel;
        $this->httpClient = $httpClient;
    }

    public function runByProvider(): string
    {
        if ($this->provider === 'open-weather-map') {
            $weatherApi = new OpenWeatherMap($this->httpClient, $this->city);
        } else if ($this->provider === 'weather-api') {
            $weatherApi = new WeatherAPI($this->httpClient, $this->city);
        } else {
            return 'Undefined provider';
        }

        $weatherApi->endpoint();
        $this->weather = $weatherApi->getWeather();

        $channel = explode(':', $this->channel);

        return match ($channel[0]) {
            'telegram' => $this->outputInTelegram($channel[1] ?? 0),
            'mail' => $this->outputInMail($channel[1] ?? ''),
            'console' => $this->outputInConsole(),
            default => 'Undefined channel',
        };
    }

    private function outputInTelegram(int $chatId): string
    {
        if ($chatId > 0) {
            $telegram = new Telegram($chatId);

            $response = $telegram->sendMessage($this->outputInConsole());

            if ($response) {
                return "Message about {$this->city}'s weather have sent to user, chat id: {$chatId}";
            } else {
                return "Message hasn't send!";
            }
        }

        return "Exception: chat id is invalid!";
    }

    private function outputInMail(string $mail): string
    {
        if ($mail != '') {
            $response = Mail::to($mail)->send(new SendWeatherMail($this->outputInConsole()));

            if ($response) {
                return "Message about {$this->city}'s weather have sent to user, mails: {$mail}";
            } else {
                return "Mail hasn't send!";
            }
        }

        return "Exception: mails is invalid!";
    }

    private function outputInConsole(): string
    {
        if ($this->getWeatherKey() != '') {
            return "According to " . $this->provider . ": Weather in " . $this->city . " is " . $this->getWeatherKey('description') . '.';
        }

        return "Exception: weather information not found";
    }

    private function getWeatherKey()
    {
        return $this->weather['text'] ?? '';
    }
}
