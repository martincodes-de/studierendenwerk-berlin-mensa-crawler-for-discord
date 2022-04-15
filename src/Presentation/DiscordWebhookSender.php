<?php

declare(strict_types=1);

namespace src\Presentation;

use GuzzleHttp\Client;
use src\Logic\Model\Meal;

final class DiscordWebhookSender
{
    public function __construct
    (
        private readonly string $webHookUrl,
        private Client $httpClient,
    )
    {
    }

    public function sendWebhook(array $meals): void
    {
        $this->httpClient->post($this->webHookUrl, [
            "json" => [
                "embeds" => [
                    [
                        "title" => $this->generateContentLine(),
                        "description" => $this->generateDescriptionLine(),
                        "fields" => $this->generateEmbedFields($meals),
                    ]
                ]
            ]
        ]);
    }

    private function generateContentLine(): string
    {
        return "\n\nHauptgerichte der HTW-Mensa Treskowallee";
    }

    private function generateDescriptionLine(): string
    {
        $date = date("d.m.Y");
        return "für den {$date}";
    }

    /**
     * @param Meal[] $meals
     * @return array
     */
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