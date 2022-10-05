<?php

namespace App\Services;

use App\Models\User;

class DiscountService
{
    private array $availableDiscounts;
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->availableDiscounts = [
            '10OFF' => [
                'type' => 'percentage',
                'amount' => '10',
                'rule' => 'userHasContract'
            ],
            'MINUS2' => [
                'type' => 'exact',
                'amount' => '2',
                'rule' => 'none'
            ]
        ];
    }

    public function checkIfDiscountIsValid($discount): bool
    {
        if(! array_key_exists($discount, $this->availableDiscounts))
        {
            return false;
        }

        $discountChosen = $this->availableDiscounts[$discount];

        return match ($discountChosen['rule']) {
            'userHasContract' => $this->user->hasTwelveMonthContract(),
            'none' => true
        };

    }

    public function calculateDiscountAmount($discount, $total): float
    {
        $discountChosen = $this->availableDiscounts[$discount];

        return match($discountChosen['type'])
        {
            'percentage' => ($total / 100) * $discountChosen['amount'],
            'exact' => $total -  $discountChosen['amount']
        };
    }

}