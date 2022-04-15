<?php

namespace src\Presentation\Logger;

class Logger
{
    private readonly string $logfilePath;

    public function __construct()
    {
        $this->logfilePath = __DIR__."/../../../public/log.txt";
    }

    public function write(LogMessageType $type, string $message): void
    {
        $currentDate = $this->getCurrentDate();
        $valueOfLogMessageType = $type->value;
        $generatedLogLine = $this->generateLogLine($valueOfLogMessageType, $message, $currentDate);

        file_put_contents($this->logfilePath, $generatedLogLine, FILE_APPEND);
    }

    private function generateLogLine(string $type, string $message, string $date): string
    {
        return "{$type} | {$message} | {$date}".PHP_EOL;
    }

    private function getCurrentDate(): string
    {
        return date("d.m.Y H:i");
    }
}