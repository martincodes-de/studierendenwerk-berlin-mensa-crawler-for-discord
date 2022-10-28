<?php

namespace src\Presentation;

use DateTime;
use DateTimeInterface;
use GuzzleHttp\Exception\GuzzleException;
use src\Logic\Converter\MealConverter;
use src\Persistence\ConfigurationDataSource;
use src\Persistence\Exceptions\NoMealsScrapedException;
use src\Persistence\MensaMealplanScraper;
use src\Presentation\Logger\Logger;
use src\Presentation\Logger\LogMessageType;

final class ApplicationRunner
{
    public function __construct(
        private MensaMealplanScraper    $scraper,
        private MealConverter           $mealConverter,
        private DiscordWebhookSender    $discordWebhookSender,
        private ConfigurationDataSource $configuration,
        private Logger                  $logger,
    )
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function start(): void {
        $dayAsWord = date("l");
        if (!$this->isScheduledDay($dayAsWord)) {
            $this->logger->write(LogMessageType::INFO, "Tried to scrap meals at {$dayAsWord}, but it's not a scheduled day.");
            return;
        }

        if (!$this->isCrawlingPaused(new DateTime(), $this->configuration->getPausedUntil())) {
            $pausedUntil = $this->configuration->getPausedUntil()->format('d.m.Y');
            $this->logger->write(LogMessageType::INFO, "Tried to scrap meals, but it's paused until {$pausedUntil}.");
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

    private function isScheduledDay(string $day): bool
    {
        return in_array($day, $this->configuration->getDaysToFetch());
    }

    private function isCrawlingPaused(DateTimeInterface $today, ?DateTimeInterface $dateUntilPaused): bool
    {
        if (is_null($dateUntilPaused)) {
            return false;
        }

        if ($today->getTimestamp() > $dateUntilPaused->getTimestamp()) {
            return false;
        }

        return true;
    }
}