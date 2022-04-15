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

    public function sendWebhook(array $meals, $mealPlanUrl): void
    {
        $this->httpClient->post($this->webHookUrl, [
            "json" => [
                "embeds" => [
                    [
                        "title" => $this->generateContentLine(),
                        "description" => $this->generateDescriptionLine(),
                        "color" => $this->selectRandomColorForEmbed(),
                        "author" => $this->generateAuthor($mealPlanUrl),
                        "fields" => $this->generateEmbedFields($meals),
                        "footer" => $this->generateFooter(),
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

    private function selectRandomColorForEmbed(): int|float
    {
        $colors = [
            "blue" => "#4287f5",
            "red" => "#f54242",
            "green" => "#42f545",
            "orange" => "#f5bf42",
            "pink" => "#f542ad",
            "turquoise" => "#42f5f5",
        ];

        $selectedColorKey = array_rand($colors);

        return hexdec($colors[$selectedColorKey]);
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

    private function generateAuthor($mealPlanWebsite): array
    {
        return [
            "name" => "studierendenWERK BERLIN",
            "url" => $mealPlanWebsite,
        ];
    }


    private function generateFooter(): array {
        return [
            "text" => "Klicke oben auf \"studierendenWERK BERLIN\", um zum ganzen Speiseplan zu kommen.",
        ];
    }
}