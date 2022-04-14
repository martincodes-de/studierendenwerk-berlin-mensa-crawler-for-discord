<?php

namespace src\Presentation;

use GuzzleHttp\Client;

class DiscordWebhookSender
{
    public function __construct
    (
        private readonly string $webHookUrl,
        private array $meals,
        private Client $httpClient,
    )
    {
    }

    public function sendWebhook(): void {
        $this->httpClient->post($this->webHookUrl, [
            "form_params" => [
                "content" => $this->generateContentLine()
            ]
        ]);
    }

    private function generateContentLine(): string
    {
        $date = date("d.m.Y");

        return "[P] Hauptgerichte für heute, den **{$date}** der HTW-Mensa Treskowallee";
    }


}