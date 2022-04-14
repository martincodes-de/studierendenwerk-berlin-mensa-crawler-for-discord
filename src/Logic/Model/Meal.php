<?php

declare(strict_types=1);

namespace src\Logic\Model;

class Meal
{
    public function __construct(
        public readonly string $meal,
        public readonly string $price,
    )
    {
    }
}