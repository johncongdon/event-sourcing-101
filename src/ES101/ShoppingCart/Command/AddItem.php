<?php

namespace ES101\ShoppingCart\Command;

use ES101\Product\Product;
use ES101\ShoppingCart\Command;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\ShoppingCart;

class AddItem implements Command
{
    public function __construct(public readonly Product $product)
    {
    }

    public function execute(ShoppingCart $cart): array
    {
        return [
            new ItemWasAdded($this->product),
        ];
    }
}