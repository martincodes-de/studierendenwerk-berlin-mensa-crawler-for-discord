<?php

declare(strict_types=1);

namespace src\Logic\Model;

final class Meal
{
    public function __construct(
        public readonly string $title,
        public readonly string $price,
    )
    {
    }
}