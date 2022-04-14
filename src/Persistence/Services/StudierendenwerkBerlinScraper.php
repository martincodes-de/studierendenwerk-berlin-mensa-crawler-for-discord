<?php

declare(strict_types=1);

namespace src\Persistence\Services;

use Symfony\Component\DomCrawler\Crawler;

class StudierendenwerkBerlinScraper
{
    public function __construct(
        private Crawler $crawler,
    )
    {
    }

    public function scrapeMainMeals()
    {
        $mainMealsSection = $this->crawler->filter("div.splGroupWrapper:nth-child(6)");
        $rawMeals = $mainMealsSection->filter(".splMeal");

        $meals = [];

        $rawMeals->each(function($node) use (&$meals) {
            $mealTitle = $node->filter("div.col-xs-6.col-md-6")->filter("span")->text();
            $mealPrices = $node->filter("div.text-right")->text();

            $meals[] = [
                "title" => $mealTitle,
                "prices" => $mealPrices,
            ];
        });

        return $meals;
    }
}