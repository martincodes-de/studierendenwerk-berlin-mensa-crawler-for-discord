<?php

declare(strict_types=1);

namespace src\Logic\Converter;

use src\Logic\Model\Meal;

final class MealConverter
{
    public function __construct(
        private PriceConverter $priceConverter
    )
    {
    }

    public function convertToMeal(array $meal): Meal
    {
        $priceForStudents = $this->priceConverter->getPriceForStudents($meal["price"]);
        return new Meal($meal["title"], $priceForStudents);
    }
}