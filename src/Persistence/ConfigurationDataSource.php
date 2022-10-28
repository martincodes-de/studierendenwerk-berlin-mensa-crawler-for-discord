<?php

namespace src\Persistence;

use DateTime;
use DateTimeInterface;
use Exception;
use src\Persistence\Exceptions\ConfigurationLoadingException;

final class ConfigurationDataSource
{
    private string $configurationUrl = __DIR__."/../../config.ini";
    private ?string $mensaWebsiteUrl = null;
    private ?string $discordWebhookUrl = null;
    private ?DateTimeInterface $pauseUntil = null;

    /*** @var String[] */
    private array $daysToFetch = [];

    public function __construct()
    {
        $configuration = $this->readConfiguration($this->configurationUrl);
        $this->setConfiguration($configuration);
    }

    public function getMensaWebsiteUrl(): string
    {
        return $this->mensaWebsiteUrl ?? "";
    }

    public function getDiscordWebhookUrl(): string
    {
        return $this->discordWebhookUrl ?? "";
    }

    /**
     * @return String[]
     */
    public function getDaysToFetch(): array
    {
        return $this->daysToFetch;
    }

    public function getPausedUntil(): ?DateTimeInterface
    {
        return $this->pauseUntil;
    }

    /**
     * @throws Exception
     * @return array<String, String>
     */
    private function readConfiguration(string $configUrl): array
    {
        $configuration = parse_ini_file($configUrl);

        if ($configuration === false) {
            throw new ConfigurationLoadingException("config.ini can't be loaded.");
        }

        return $configuration;
    }


    /**
     * @param array<String, String> $configuration
     * @return void
     */
    private function setConfiguration(array $configuration): void {
        $this->discordWebhookUrl = $configuration["discord_webhook_url"];
        $this->mensaWebsiteUrl = $configuration["studierendenwerk_berlin_mensa_website"];
        $this->daysToFetch = explode(",", $configuration["days_to_fetch"]);
        $this->pauseUntil = $this->extractPauseUntilFromRawConfigurationFile($configuration["pause_crawling_until"]);
    }

    private function extractPauseUntilFromRawConfigurationFile(?string $pauseUntilConfigurationEntry): ?DateTimeInterface
    {
        if (empty($pauseUntilConfigurationEntry)) {
            return null;
        }

        try {
            return new DateTime($pauseUntilConfigurationEntry);
        } catch (Exception $e) {
            return null;
        }
    }
}