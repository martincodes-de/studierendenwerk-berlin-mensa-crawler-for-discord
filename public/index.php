<?php

use Goutte\Client;
use src\Presentation\PriceConverter;
use src\Presentation\ViewModel\Meal;

require_once "../vendor/autoload.php";

$client = new Client();
$scraper = $client->request("GET", "https://www.stw.berlin/mensen/einrichtungen/hochschule-f%C3%BCr-technik-und-wirtschaft-berlin/mensa-htw-treskowallee.html");

$mainMealsSection = $scraper->filter("div.splGroupWrapper:nth-child(6)");
$meals = $mainMealsSection->filter(".splMeal");

$meals->each(function($node) {
    $mealTitle = $node->filter("div.col-xs-6.col-md-6")->filter("span")->text();
    $mealPrices = $node->filter("div.text-right")->text();

    $meal = new Meal($mealTitle, PriceConverter::getPriceForStudents($mealPrices));
});