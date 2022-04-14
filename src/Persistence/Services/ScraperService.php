<?php

namespace src\Persistence\Services;

use Symfony\Component\DomCrawler\Crawler;

class ScraperService
{
    public function __construct(
        private Crawler $crawler,
        private array $meals = [],
    )
    {
    }

    public function scrapeMainMeals() {
        $mainMealsSection = $this->crawler->filter("div.splGroupWrapper:nth-child(6)");
        $meals = $mainMealsSection->filter(".splMeal");

        $meals->each(function($node) {
            $mealTitle = $node->filter("div.col-xs-6.col-md-6")->filter("span")->text();
            $mealPrices = $node->filter("div.text-right")->text();

            $this->meals[] = [
                "title" => $mealTitle,
                "prices" => $mealPrices
            ];
        });
    }

    public function getMeals(): array {
        return $this->meals;
    }
}