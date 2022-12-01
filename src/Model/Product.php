<?php

namespace App\Model;

class Product
{
    private $id;
    private $price;
    private $priceWithDiscount;


    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
        return $this;
    }

    public function setPriceWithDiscount(int $price): self
    {
        $this->priceWithDiscount = $price;
        return $this;
    }

    public function getPriceWithDiscount(): ?int
    {
        return $this->priceWithDiscount;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function serialize()
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'price_with_discount' => $this->priceWithDiscount,
        ];
    }


}