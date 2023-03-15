<?php

namespace ES101\ShoppingCart\Command;

use ES101\Product\ProductService;
use ES101\ShoppingCart\Command;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\Event\ItemWasRemoved;
use ES101\ShoppingCart\ShoppingCart;

class RemoveItem implements Command
{
    public function __construct(public readonly ProductService $product)
    {
    }

    public function execute(ShoppingCart $cart): array
    {
        return [
            new ItemWasRemoved($this->product),
        ];
    }
}