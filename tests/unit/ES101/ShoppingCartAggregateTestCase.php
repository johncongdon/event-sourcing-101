<?php

namespace ES101\Tests\Unit\ES101;

use ES101\Product\ProductService;
use ES101\ShoppingCart\Command;
use ES101\ShoppingCart\ShoppingCart;
use ES101\ShoppingCart\ShoppingCartId;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

class ShoppingCartAggregateTestCase extends AggregateRootTestCase
{
    protected ProductService $product_service;

    public function setUp(): void
    {
        $this->product_service = new ProductService();
    }

    protected function handle(Command ...$commands): void
    {
        $aggregate = $this->getSut();

        foreach ($commands as $command) {
            $aggregate->process($command);
        }

        $this->repository->persist($aggregate);
    }

    protected function getSut(): ShoppingCart
    {
        return $this->repository->retrieve($this->aggregateRootId());
    }


    protected function newAggregateRootId(): AggregateRootId
    {
        return new ShoppingCartId();
    }

    protected function aggregateRootClassName(): string
    {
        return ShoppingCart::class;
    }
}