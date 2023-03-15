<?php

namespace ES101\Tests\Unit\ES101;

use ES101\Product\Product;
use ES101\ShoppingCart\Command\AddItem;
use ES101\ShoppingCart\Command\RemoveItem;
use ES101\ShoppingCart\Event\CartWasInitialized;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\Event\ItemWasRemoved;
use ES101\ShoppingCart\LineItem;
use Money\Money;

class ShoppingCartTest extends ShoppingCartAggregateTestCase
{
    /**
     * @test
     */
    public function it_must_be_initialized(): void
    {
        $qty = 1;
        $product_id = 1;
        $product = $this->product_service->findById($product_id);

        $this->when(new AddItem($product, $qty))
            ->expectToFail(new \InvalidArgumentException('Cart Must be initialized'));
    }

    /**
     * @test
     */
    public function an_item_can_be_added(): void
    {
        $qty = 1;
        $product_id = 1;
        $product = $this->product_service->findById($product_id);
        $line_item = new LineItem($product, $qty);

        $this->given(new CartWasInitialized())
            ->when(new AddItem($product, $qty))
            ->then(new ItemWasAdded($line_item));

        $sut = $this->getSut();
        self::assertCount(1, $sut->getItems());
    }

    /**
     * @test
     */
    public function multiple_items_can_be_added(): void
    {
        $qty = 1;
        $product_1 = $this->product_service->findById(1);
        $line_item_1 = new LineItem($product_1, $qty);
        $product_2 = $this->product_service->findById(2);
        $line_item_2 = new LineItem($product_2, $qty);

        $this->given(new CartWasInitialized())
            ->when(new AddItem($product_1, $qty))
            ->when(new AddItem($product_2, $qty))
            ->then(new ItemWasAdded($line_item_1))
            ->then(new ItemWasAdded($line_item_2));

        $sut = $this->getSut();
        self::assertCount(2, $sut->getItems());
    }

    /**
     * @test
     */
    public function adding_the_same_product_twice_increased_qty(): void
    {
        $product_1 = $this->product_service->findById(1);
        $product_2 = $this->product_service->findById(1);

        $expected_line_item_1 = new LineItem($product_1, 1);
        $expected_line_item_2 = new LineItem($product_2, 3);

        $this->given(new CartWasInitialized())
            ->when(new AddItem($product_1, 1))
            ->when(new AddItem($product_2, 2))
            ->then(new ItemWasAdded($expected_line_item_1))
            ->then(new ItemWasAdded($expected_line_item_2));

        $sut = $this->getSut();
        self::assertCount(1, $sut->getItems());
        self::assertSame(3, $sut->getItems()[1]->qty);
    }
    
    /**
     * @test
     */
    public function removing_an_item_adjusts_cart()
    {
        $product_1 = $this->product_service->findById(1);

        $expected_line_item_1 = new LineItem($product_1, 1);

        $this->given(new CartWasInitialized())
            ->when(new AddItem($product_1, 1))
            ->when(new RemoveItem($product_1))
            ->then(new ItemWasAdded($expected_line_item_1))
            ->then(new ItemWasRemoved($product_1));

        $sut = $this->getSut();
        self::assertCount(0, $sut->getItems());

    }
}