<?php

namespace App;

use App\Models\Basket;
use App\Models\Item;
use App\Models\User;
use App\Services\DiscountService;

class StoreController {

    public Basket $basket;
    private User $user;

    public function __construct(Basket $basket, User $user)
    {
        $this->basket = $basket;
        $this->user = $user;
    }

    public function addItemToBasket(Item $item)
    {
        $this->basket->addItem($item);
    }

    public function getBasketItems(): array
    {
        return $this->basket->items;
    }

    public function getBasketTotal()
    {
        return $this->basket->getTotal();
    }

    /**
     * @throws \Exception
     */
    public function applyDiscountToBasket($discountCode)
    {
        $discountService = new DiscountService($this->user);

        if($discountService->checkIfDiscountIsValid($discountCode))
        {
            $discount = $discountService->calculateDiscountAmount($discountCode, $this->basket->getTotal());
            $this->basket->discount = $discount;
            return $this->basket->getTotal();
        }
        
        throw new \Exception('Sorry, this discount is not available.');

    }



}