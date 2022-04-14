<?php

declare(strict_types=1);

namespace src\Persistence\Services;

use Symfony\Component\DomCrawler\Crawler;

class ScraperService
{
    public function __construct(
        private Crawler $crawler,
    )
    {
    }

    public function scrapeMainMeals()
    {
        $mainMealsSection = $this->crawler->filter("div.splGroupWrapper:nth-child(6)");
        $meals = $mainMealsSection->filter(".splMeal");

        $scrapedMeals = [];

        $meals->each(function($node) use (&$scrapedMeals) {
            $mealTitle = $node->filter("div.col-xs-6.col-md-6")->filter("span")->text();
            $mealPrices = $node->filter("div.text-right")->text();

            $scrapedMeals[] = [
                "title" => $mealTitle,
                "prices" => $mealPrices,
            ];
        });

        return $scrapedMeals;
    }
}