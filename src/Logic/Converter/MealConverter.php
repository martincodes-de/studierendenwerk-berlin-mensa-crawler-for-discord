<?php

declare(strict_types=1);

namespace src\Logic\Converter;

use src\Logic\Model\Meal;

final class MealConverter
{
    public function convertToMeal(array $meal): Meal
    {
        return new Meal($meal["title"], $meal["price"]);
    }
}