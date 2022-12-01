<?php

namespace App\Model;

class ProductManager
{
    public function createProductList(array $list = [])
    {
        $productList = new ProductList();
        array_map(function ($row) use (&$productList) {
            $productList->add($this->createProduct($row));
        }, $list);

        return $productList;
    }

    private function createProduct(array $row)
    {
        if ($row) {
            $item = new Product();
            $item->setId($row['id'] ?? 0);
            $item->setPrice($row['price'] ?? 0);

            return $item;
        }
    }

}