<?php

namespace Tests\Unit;

use App\Models\Basket;
use App\Models\Item;
use App\Models\User;
use App\ProductController;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use App\StoreController;

class StoreTest extends TestCase
{

    private ProductController $productController;

    public function setUp(): void
    {
        parent::setUp();

        $this->productController = new ProductController();
        $this->createProducts();
    }

    public function testsGettingProducts()
    {
        $this->assertCount(4, $this->productController->getProducts());
    }

    public function testsAddingItemToBasket()
    {
        $user = $this->getUser();
        $basket = new Basket([], $user->id);
        $store = new StoreController($basket, $user);
        $products = $this->productController->getProducts();
        $store->addItemToBasket($products[0]);

        $this->assertCount(1, $store->getBasketItems());
    }

    public function testsGettingBasketTotal()
    {
        $user = $this->getUser();
        $basket = new Basket([], $user->id);
        $store = new StoreController($basket, $user);
        $products = $this->productController->getProducts();
        $store->addItemToBasket($products[0]);
        $store->addItemToBasket($products[2]);

        $this->assertEquals(283.50, $store->getBasketTotal());
    }

    /**
     * @throws \Exception
     */
    public function testsApplyingDiscountToBasketFailure()
    {
        $this->expectExceptionMessage('Sorry, this discount is not available.');

        $user = $this->getUser();
        $basket = new Basket([], $user->id);
        $store = new StoreController($basket, $user);
        $products = $this->productController->getProducts();
        $store->addItemToBasket($products[0]);
        $store->addItemToBasket($products[2]);

        $store->applyDiscountToBasket('10OFF');
    }

    public function testsApplyingDiscountToBasketSuccess()
    {
        $user = $this->getUser(true);
        $basket = new Basket([], $user->id);
        $store = new StoreController($basket, $user);
        $products = $this->productController->getProducts();
        $store->addItemToBasket($products[0]);
        $store->addItemToBasket($products[2]);

        $store->applyDiscountToBasket('10OFF');

        $this->assertEquals(255.15, $store->getBasketTotal());
    }

    public function testAddingDuplicateItemsToBasket()
    {
        $this->expectExceptionMessage('You can only have one of any given item in your basket.');

        $user = $this->getUser();
        $basket = new Basket([], $user->id);
        $store = new StoreController($basket, $user);
        $products = $this->productController->getProducts();
        $store->addItemToBasket($products[0]);
        $store->addItemToBasket($products[0]);
    }

    private function getUser($hasContract = false): User
    {
        $userDetails = [
            1,
            'Test User',
            'test@test.com'
        ];

        if($hasContract)
        {
            $userDetails = array_merge($userDetails, [
                Carbon::now(),
                Carbon::now()->addYear()
            ]);
        }

        return new User(...$userDetails);
    }

    private function createProducts(): void
    {
        $products = [
            'P001' => [
                'name' => 'Photography',
                'price' => 200
            ],
            'P002' => [
                'name' => 'Floorplan',
                'price' => 100
            ],
            'P003' => [
                'name' => 'Gas Certificate',
                'price' => 83.50
            ],
            'P004' => [
                'name' => 'EICR Certificate',
                'price' => 51
            ]
        ];

        foreach ($products as $code => $item)
        {
            $this->productController->addProduct(new Item($code,$item['name'],$item['price']));
        }
    }
}