<?php

namespace App\Console\Commands\Weather;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Weather\Services\WeatherControlService;

class WeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather {provider} {city} {channel?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the current weather for a given city';

    /**
     * Execute the console command.
     */
    public function handle(Client $httpClient): void
    {
        $provider = $this->argument('provider');
        $city = $this->argument('city');
        $channel = $this->argument('channel') ?? 'console';

        $weatherProvider = new WeatherControlService($provider, $city, $channel, $httpClient);

        $result = $weatherProvider->runByProvider();

        $this->info($result);

        $this->comment('Command run successfully');
    }
}
