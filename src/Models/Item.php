<?php

namespace App\Models;

class Item
{
    public string $code;
    public string $name;
    public float $price;

    public function __construct($code, $name, $price)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

}