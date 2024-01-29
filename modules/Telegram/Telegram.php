<?php

namespace Modules\Telegram;

use Modules\Telegram\Traits\TelegramBotMethods;

class Telegram
{
    use TelegramBotMethods;

    private string $botApiToken;
    private ?int $chatId;

    public function __construct(int $chatId)
    {
        $this->botApiToken = $this->setBotApiToken();
        $this->chatId = $chatId;
    }

    private function setBotApiToken()
    {
        return config('telegram.bots.weather.api-token');
    }

    public function getBotApiToken(): string
    {
        return $this->botApiToken;
    }

    public function getChatId(): int|null
    {
        return $this->chatId;
    }
}
