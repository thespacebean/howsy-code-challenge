<?php

namespace App\Models;

class Basket
{
    public array $items;
    public ?string $discount;
    public string $userId;

    public function __construct($items, $userId, $discount = null)
    {
        $this->items = $items;
        $this->userId = $userId;
        $this->discount = $discount;
    }

    public function addItem(Item $item)
    {
        array_push($this->items, $item);
    }

    public function getTotal()
    {
        $baseTotal = array_sum(
            array_column($this->items, 'price')
        );

        return $this->applyDiscount($baseTotal);
    }

    public function applyDiscount($total)
    {
        return $total - $this->discount;
    }


}