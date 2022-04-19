<?php

declare(strict_types=1);

namespace src\Logic\Converter;

final class PriceConverter
{
    public function getPriceForStudents(string $prices): string
    {
        $cleanedPrices = $this->getCleanedPrices($prices);
        $priceList = $this->getSinglePrices($cleanedPrices);
        $priceForStudents = $priceList[0];
        return $this->getPriceWithEuroSign($priceForStudents);
    }

    private function getCleanedPrices(string $prices): string
    {
        return str_replace("€ ", "", $prices);
    }


    /**
     * @param string $prices
     * @return String[]
     */
    private function getSinglePrices(string $prices): array
    {
        return explode("/", $prices);
    }

    private function getPriceWithEuroSign(string $price): string
    {
        return "{$price}€";
    }
}