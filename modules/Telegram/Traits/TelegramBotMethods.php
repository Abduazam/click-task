<?php

namespace Modules\Telegram\Traits;

use Throwable;

trait TelegramBotMethods
{
    private function sendRequest($url, array $content): bool|string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function endpoint(string $api, array $content)
    {
        $url = 'https://api.telegram.org/bot' . $this->getBotApiToken() . '/' . $api;

        $reply = $this->sendRequest($url, $content);

        return json_decode($reply, true);
    }

    public function sendMessage(string $message)
    {
        return $this->endpoint('sendMessage', [
            'chat_id' => $this->getChatId(),
            'text' => $message,
            'parse_mode' => 'html',
        ]);
    }
}
