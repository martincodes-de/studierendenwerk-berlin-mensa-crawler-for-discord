<?php

namespace src\Presentation;

use src\Logic\Converter\MealConverter;
use src\Persistence\ConfigurationDataSource;
use src\Persistence\Exceptions\NoMealsScrapedException;
use src\Persistence\MensaHTWTreskowalleeScraper;
use src\Presentation\Logger\Logger;
use src\Presentation\Logger\LogMessageType;
use Symfony\Component\DomCrawler\Crawler;

final class ApplicationRunner
{
    public function __construct(
        private MensaHTWTreskowalleeScraper $scraper,
        private MealConverter $mealConverter,
        private DiscordWebhookSender $discordWebhookSender,
        private ConfigurationDataSource $configuration,
        private Logger $logger,
    )
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function start(): void {
        $dayAsWord = date("l");
        if (!$this->isWeekday($dayAsWord)) return;

        try {
            $scrapedMeals = $this->scraper->scrapeMainMeals();

            $meals = array_map(function($meal) {
                return $this->mealConverter->convertToMeal($meal);
            }, $scrapedMeals);

            $this->discordWebhookSender->sendWebhook($meals, $this->configuration->getScrapedWebsiteUrl());
        } catch (NoMealsScrapedException $e) {
            $this->logger->write(LogMessageType::ERROR, $e->getMessage());
        }
    }

    private function isWeekday(string $day): bool
    {
        $weekdays = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
        ];

        return in_array($day, $weekdays);
    }
}