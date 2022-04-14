<?php

declare(strict_types=1);

namespace src\Logic;

final class PriceConverter
{
    public static function getPriceForStudents($prices): string {
        $cleanedPrices = self::getCleanedPrices($prices);
        $priceList = self::getSinglePrices($cleanedPrices);
        $priceForStudents = $priceList[0];
        return self::getPriceWithEuroSign($priceForStudents);
    }

    private static function getCleanedPrices(string $prices): string
    {
        return str_replace("€ ", "", $prices);
    }

    private static function getSinglePrices(string $prices): array
    {
        return explode("/", $prices);
    }

    private static function getPriceWithEuroSign(string $price): string
    {
        return "{$price}€";
    }
}