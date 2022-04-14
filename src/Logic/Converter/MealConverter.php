<?php

namespace src\Logic\Converter;

use src\Logic\Model\Meal;

class MealConverter
{
    public function convertToMeal(array $meal): Meal
    {
        return new Meal($meal["title"], $meal["price"]);
    }
}