<?php

declare(strict_types=1);

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
                "content" => $this->generateContentLine(),
                "embeds" => [
                    ["fields" => $this->generateEmbed($this->meals)]
                ]
            ]
        ]);
    }

    private function generateContentLine(): string
    {
        $date = date("d.m.Y");
        return "[P] Hauptgerichte für heute, den **{$date}** der HTW-Mensa Treskowallee:";
    }

    private function generateEmbed(array $meals) {
        $mealFields = [];

        foreach ($meals as $meal) {
            $mealFields[] = [
                "name" => $meal->title,
                "value" => $meal->price,
            ];
        }

        return $mealFields;
    }


}