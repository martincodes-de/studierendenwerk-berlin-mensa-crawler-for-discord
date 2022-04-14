<?php

namespace src\Presentation\ViewModel;

class Meal
{
    public function __construct(
        public readonly string $meal,
        public readonly string $price,
    )
    {
    }
}