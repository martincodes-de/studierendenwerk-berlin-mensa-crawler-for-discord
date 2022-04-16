<?php

use Goutte\Client;
use GuzzleHttp\Client as HttpClient;
use src\Logic\Converter\MealConverter;
use src\Logic\Converter\PriceConverter;
use src\Persistence\ConfigurationDataSource;
use src\Persistence\MensaHTWTreskowalleeScraper;
use src\Presentation\ApplicationRunner;
use src\Presentation\DiscordWebhookSender;
use src\Presentation\Logger\Logger;
use src\Presentation\Logger\LogMessageType;

require_once __DIR__."/../vendor/autoload.php";

$configuration = new ConfigurationDataSource();
$logger = new Logger();

$client = new Client();
$crawler = $client->request("GET", $configuration->getMensaWebsiteUrl());

$scraper = new MensaHTWTreskowalleeScraper($crawler);

$httpClient = new HttpClient();
$priceConverter = new PriceConverter();
$mealConverter = new MealConverter($priceConverter);

$discordWebhookSender = new DiscordWebhookSender($configuration->getDiscordWebhookUrl(), $httpClient);

$appRunner = new ApplicationRunner($scraper, $mealConverter, $discordWebhookSender, $configuration, $logger);

try {
    $appRunner->start();
} catch (Exception $e) {
    $logger->write(LogMessageType::ERROR, $e->getMessage());
    die($e->getMessage());
}
