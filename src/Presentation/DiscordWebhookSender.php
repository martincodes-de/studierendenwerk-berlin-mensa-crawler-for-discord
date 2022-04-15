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
            "form_params" => [
                "content" => $this->generateContentLine($meals),
                "embeds" => [
                    [
                        "fields" => $this->generateEmbedFields($meals)
                    ]
                ]
            ]
        ]);
    }

    /**
     * @param Meal[] $meals
     * @return string
     */
    private function generateContentLine(array $meals): string
    {
        $date = date("d.m.Y");
        $content = "\n\nHauptgerichte für heute, den **{$date}** der HTW-Mensa Treskowallee: \n\n";

        foreach ($meals as $meal) {
            $content .= ":fork_knife_plate: {$meal->title}: {$meal->price} \n";
        }
        $content .= "\n\n";

        return $content;
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