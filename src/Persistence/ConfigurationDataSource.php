<?php

namespace src\Persistence;

use Exception;
use src\Persistence\Exceptions\ConfigurationLoadingException;

final class ConfigurationDataSource
{
    private string $configurationUrl = __DIR__."/../../config.ini";
    private ?string $scrapedWebsiteUrl = null;
    private ?string $discordWebhookUrl = null;

    public function __construct()
    {
        $configuration = $this->readConfiguration($this->configurationUrl);
        $this->setConfiguration($configuration);
    }

    public function getScrapedWebsiteUrl(): string
    {
        return $this->scrapedWebsiteUrl ?? "";
    }

    public function getDiscordWebhookUrl(): string
    {
        return $this->discordWebhookUrl ?? "";
    }

    /**
     * @throws Exception
     * @return array<String, String>
     */
    private function readConfiguration(string $configUrl): array
    {
        $configuration = parse_ini_file($configUrl);

        if ($configuration === false) {
            throw new ConfigurationLoadingException("Configuration.ini can't be loaded.");
        }

        return $configuration;
    }


    /**
     * @param array<String, String> $configuration
     * @return void
     */
    private function setConfiguration(array $configuration): void {
        $this->discordWebhookUrl = $configuration["discord_webhook_url"];
        $this->scrapedWebsiteUrl = $configuration["studierendenwerk_berlin_mensa_website"];
    }
}