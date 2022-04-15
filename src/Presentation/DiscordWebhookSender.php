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
                        "fields" => $this->generateEmbedFields($meals),
                    ]
                ]
            ]
        ]);
    }

    private function generateContentLine(): string
    {
        $date = date("d.m.Y");
        return "\n\nHauptgerichte für heute, den **{$date}** der HTW-Mensa Treskowallee: \n";
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