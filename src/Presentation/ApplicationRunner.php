<?php

namespace src\Presentation;

use src\Logic\Converter\MealConverter;
use src\Persistence\ConfigurationDataSource;
use src\Persistence\MensaHTWTreskowalleeScraper;
use Symfony\Component\DomCrawler\Crawler;

class ApplicationRunner
{
    public function __construct(
        private MensaHTWTreskowalleeScraper $scraper,
        private MealConverter $mealConverter,
        private ConfigurationDataSource $configuration,
    )
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function start(): void {
        $dayAsWord = date("l");
        if (!$this->isWeekday($dayAsWord)) return;

        $scrapedMeals = $this->scraper->scrapeMainMeals();

        if (count($scrapedMeals) < 1) {
            throw new \Exception("No meals scraped.");
        }

        $meals = array_map(function($meal) {
            return $this->mealConverter->convertToMeal($meal);
        }, $scrapedMeals);

        
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