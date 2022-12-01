<?php

namespace App\Model;

class ProductList
{
    /** @var Product[] */
    private $list = [];

    public function add(Product $item)
    {
        if (!$this->exists($item)) {
            $this->list[] = $item;
        }
        return $this;
    }

    public function exists(Product $item)
    {
        $id = $item->getId();
        $exists = false;
        foreach ($this->list as $listItem) {
            if ($listItem->getId() == $id) {
                $exists = true;
                break;
            }
        }

        return $exists;
    }


    public function calculateSumm(): int
    {
        $sum = 0;
        if (!$this->list) {
            return $sum;
        }
        foreach ($this->list as $item) {
            $sum += $item->getPrice();
        }

        return $sum;
    }

    public function calculateDiscountSumm(): int
    {
        $sum = 0;
        if (!$this->list) {
            return $sum;
        }
        foreach ($this->list as $item) {
            $sum += $item->getPriceWithDiscount();
        }

        return $sum;
    }

    public function addDiffToMostExpensive(int $diff)
    {
        if ($mostExpensive = $this->getMostExpensive()) {
            $priceWithDiscount = $mostExpensive->getPriceWithDiscount() + $diff;
            $mostExpensive->setPriceWithDiscount($priceWithDiscount);
        }

    }

    public function getMostExpensive(): ?Product
    {
        $mostExpensive = null;
        if ($this->list) {
            $max = 0;
            foreach ($this->list as $item) {
                if ($item->getPrice() > $max) {
                    $max = $item->getPrice();
                    $mostExpensive = $item;
                }
            }
        }
        return $mostExpensive;
    }

    public function serializeList()
    {
        return array_filter(array_map(function ($item) {
            return $item->serialize();
        }, $this->list));
    }

    public function applyDiscount($discount)
    {
        foreach ($this->list as &$item) {

            $this->setItemPriceWithDiscount($item, $discount);
        }
    }

    private function setItemPriceWithDiscount(Product $item, $discount)
    {
        $price = $item->getPrice();
        $priceWithDiscount = round($price * $discount, 0);
        $item->setPriceWithDiscount($priceWithDiscount);
    }

    public function count()
    {
        return count($this->list);
    }

    public function resolveDiscountDiff($diff)
    {
        $step = $diff > 0 ? 1 : -1;
        $revStep = $step * (-1);
        foreach ($this->list as $item) {
            $item->setPriceWithDiscount($item->getPriceWithDiscount() + $step);
            $diff += $revStep;
            if (!$diff) {
                break;
            }
        }
    }
}