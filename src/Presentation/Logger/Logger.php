<?php

namespace src\Presentation\Logger;

class Logger
{
    private readonly string $logfilePath;

    public function __construct(
        private LogMessageType $type,
        private string $message,
    )
    {
        $this->logfilePath = __DIR__."/../../../public/log.txt";
    }

    public function write(): void
    {
        $currentDate = $this->getCurrentDate();
        $valueOfLogMessageType = $this->getValueOfLogMessageType();
        $generatedLogLine = $this->generateLogLine($valueOfLogMessageType, $this->message, $currentDate);

        file_put_contents($this->logfilePath, $generatedLogLine, FILE_APPEND);
    }

    private function generateLogLine(string $type, string $message, string $date): string
    {
        return "{$type} | {$message} | {$date}";
    }

    private function getValueOfLogMessageType(): string
    {
        return $this->type->value;
    }

    private function getCurrentDate(): string
    {
        return date("d.m.Y H:i");
    }
}