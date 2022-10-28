<?php

use Goutte\Client;
use GuzzleHttp\Client as HttpClient;
use src\Logic\Converter\MealConverter;
use src\Logic\Converter\PriceConverter;
use src\Persistence\ConfigurationDataSource;
use src\Persistence\MensaMealplanScraper;
use src\Presentation\ApplicationRunner;
use src\Presentation\DiscordWebhookSender;
use src\Presentation\Logger\Logger;
use src\Presentation\Logger\LogMessageType;

require_once __DIR__."/../vendor/autoload.php";

$logger = new Logger();

try {
    $configuration = new ConfigurationDataSource();

    $client = new Client();
    $crawler = $client->request("GET", $configuration->getMensaWebsiteUrl());
    $scraper = new MensaMealplanScraper($crawler);

    $httpClient = new HttpClient();
    $priceConverter = new PriceConverter();
    $mealConverter = new MealConverter($priceConverter);
    $discordWebhookSender = new DiscordWebhookSender($configuration->getDiscordWebhookUrl(), $httpClient);

    $appRunner = new ApplicationRunner($scraper, $mealConverter, $discordWebhookSender, $configuration, $logger);
    $appRunner->start();
} catch (Exception $e) {
    $logger->write(LogMessageType::ERROR, $e->getMessage());
}