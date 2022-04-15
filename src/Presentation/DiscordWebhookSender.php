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

    public function sendWebhook(): void
    {
        $this->httpClient->post($this->webHookUrl, [
            "form_params" => [
                "content" => $this->generateContentLine(),
                "embeds" => [
                    [
                        "fields" => $this->generateEmbedFields($this->meals)
                    ]
                ]
            ]
        ]);
    }

    private function generateContentLine(): string
    {
        $date = date("d.m.Y");
        return "Hauptgerichte für heute, den **{$date}** der HTW-Mensa Treskowallee: \n";
    }

    private function generateEmbedFields(array $meals): array
    {
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