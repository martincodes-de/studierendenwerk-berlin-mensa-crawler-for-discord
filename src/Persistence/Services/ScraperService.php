<?php

namespace src\Persistence\Services;

use Symfony\Component\DomCrawler\Crawler;

class ScraperService
{
    public function __construct(
        private Crawler $crawler,
    )
    {
    }

    public function scrapeMainMeals(): array {
        $this->crawler
    }
}