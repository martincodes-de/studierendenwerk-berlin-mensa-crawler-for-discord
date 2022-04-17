<?php

namespace src\Presentation;

use GuzzleHttp\Exception\GuzzleException;
use src\Logic\Converter\MealConverter;
use src\Persistence\ConfigurationDataSource;
use src\Persistence\Exceptions\NoMealsScrapedException;
use src\Persistence\MensaHTWTreskowalleeScraper;
use src\Presentation\Logger\Logger;
use src\Presentation\Logger\LogMessageType;

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
        if (!$this->isWeekday($dayAsWord)) {
            $this->logger->write(LogMessageType::INFO, "Tryed to scrap meals at {$dayAsWord}, but it's not scheduled.");
            return;
        }

        try {
            $scrapedMeals = $this->scraper->scrapeMainMeals();

            $meals = array_map(function($meal) {
                return $this->mealConverter->convertToMeal($meal);
            }, $scrapedMeals);

            $this->discordWebhookSender->sendWebhook($meals, $this->configuration->getMensaWebsiteUrl());
        } catch (NoMealsScrapedException | GuzzleException $e) {
            $this->logger->write(LogMessageType::ERROR, $e->getMessage());
        }
    }

    private function isWeekday(string $day): bool
    {
        return in_array($day, $this->configuration->getDaysToFetch());
    }
}