<?php

declare(strict_types=1);

namespace src\Persistence;

use src\Persistence\Exceptions\NoMealsScrapedException;
use Symfony\Component\DomCrawler\Crawler;

final class MensaMealplanScraper
{
    public function __construct(
        private Crawler $crawler,
    )
    {
    }

    /**
     * @throws NoMealsScrapedException
     * @return array<Int, array<String, String>>
     */
    public function scrapeMainMeals(): array
    {
        $mainMealsSection = $this->getMainMealsSection();
        if (is_null($mainMealsSection)) {
            throw new NoMealsScrapedException("No meals scraped because the main meals section was not found (returned null)");
        }

        $rawMeals = $mainMealsSection->filter(".splMeal");
        if ($rawMeals->count() < 1) {
            throw new NoMealsScrapedException("No meals scraped.");
        }

        $meals = [];

        $rawMeals->each(function($node) use (&$meals) {
            $mealTitle = $node->filter("div.col-xs-6.col-md-5")->filter("span")->text();
            $mealPrices = $node->filter("div.text-right")->text();

            $meals[] = [
                "title" => $mealTitle,
                "prices" => $mealPrices,
            ];
        });

        return $meals;
    }

    private function getMainMealsSection(): ?Crawler
    {
        $mainMealsSection = null;

        $mealsSections = $this->crawler->filter("div.splGroupWrapper");
        $mealsSections->each(function($section) use (&$mainMealsSection) {
            if ($section->filter(".splGroup")->text() == "Essen") {
                $mainMealsSection = $section;
            }
        });

        return $mainMealsSection;
    }
}