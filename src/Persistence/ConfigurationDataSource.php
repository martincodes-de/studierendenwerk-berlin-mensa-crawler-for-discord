<?php

namespace src\Persistence;

use Exception;

class ConfigurationDataSource
{
    private string $configurationUrl = __DIR__."/../../config.ini";
    private ?string $scrapedWebsiteUrl = null;
    private ?string $discordWebhookUrl = null;

    public function __construct()
    {
        $configuration = $this->readConfiguration($this->configurationUrl);
        $this->setConfiguration($configuration);
    }

    public function getScrapedWebsiteUrl(): ?string
    {
        return $this->scrapedWebsiteUrl;
    }

    public function getDiscordWebhookUrl(): ?string
    {
        return $this->discordWebhookUrl;
    }

    /**
     * @throws Exception
     */
    private function readConfiguration($configUrl): array
    {
        $configuration = parse_ini_file($configUrl);

        if ($configuration === false) {
            throw new Exception("Configuration.ini can't be loaded.");
        }

        return $configuration;
    }

    private function setConfiguration(array $configuration): void {
        $this->discordWebhookUrl = $configuration["discord_webhook_url"];
        $this->scrapedWebsiteUrl = $configuration["studierendenwerk_mensa_website"];
    }
}