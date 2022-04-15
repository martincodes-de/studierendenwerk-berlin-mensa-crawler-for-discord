<?php

namespace src\Presentation;

class ApplicationRunner
{
    public function __construct(

    )
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function start(): void {
        
    }

    private function isWeekday(string $day): bool
    {
        $weekdays = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
        ];

        return in_array($day, $weekdays);
    }
}