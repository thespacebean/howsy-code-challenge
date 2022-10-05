<?php

namespace App;

use App\Models\Item;

class ProductController
{
    private array $products;

    public function __construct()
    {
        $this->products = [];
    }

    public function addProduct(Item $item)
    {
        array_push($this->products, $item);
    }

    public function getProducts(): array
    {
        return $this->products;
    }

}